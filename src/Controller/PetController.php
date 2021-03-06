<?php

namespace App\Controller;

use App\DTO\PetDTO;
use App\Entity\Pet;
use App\Service\PetService;
use App\Form\PetForm;
use App\Form\SearchForm;
use App\Exception\EntityNotDeletedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PetController extends AbstractController
{
    private $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchForm::class, $petDTO = new PetDTO());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pets = $this->petService->searchPets($petDTO);
        } else {
            $pets = $this->petService->loadPets();
        }

        return $this->render("pet/pet_show_all.html.twig", [
            'form' => $form->createView(),
            'pets' => $pets
        ]);
    }

    /**
     * @Route("/new", name="new_pet")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request) : Response
    {
        // creates a pet and creates a form for adding new pet
        $form = $this->createForm(PetForm::class, $pet = new Pet(), [
            'validation_groups' => ['Pet', 'new']
        ]);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->petService->newPet($pet);
            return $this->redirectToRoute('index');
        }
        return $this->render('pet/pet_new.html.twig', array(
            'form' => $form->createView(), 'pet' => $pet
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit_pet")     *
     * @Security("is_granted('edit', pet, 'Not allowed! You are not the owner.')")
     */
    public function editPet(Pet $pet, Request $request): Response
    {
        $form = $this->createForm(PetForm::class, $pet, [
            'validation_groups' => ['Pet', 'edit']
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPhotos = $pet->getPhotos()->getSnapshot();
            $pet->setPhotos($pet->getPhotos()->unwrap());
            foreach ($oldPhotos as $photo) {
                $pet->addPhoto($photo);
            }

            $this->petService->saveChanges($pet);
            return $this->redirectToRoute('index');
        }

        return $this->render('pet/pet_new.html.twig', [
            'form' => $form->createView(), 'pet' => $pet
        ]);
    }

    /**
     * @Route("/photo/delete/{photoName}", name="delete_photo")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function deletePhoto(string $photoName): Response
    {
        try {
            $this->petService->deletePhoto($photoName);
            return new Response(null, 204);
        } catch (EntityNotDeletedException $exception) {
            return new Response($exception->getMessage(), 400);
        }
    }

    /**
     * @Route("/pet/delete/{petId}", name="delete_pet")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function deletePet(string $petId): Response
    {
        try {
            $pet = $this->petService->findById($petId);
            $this->petService->deletePet($pet);
            return new Response(null, 204);
        } catch (EntityNotDeletedException $exception) {
            return new Response($exception->getMessage(), 400);
        }
    }
}
