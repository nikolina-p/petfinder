<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Entity\Photo;
use App\Service\PetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PetForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PetController extends AbstractController
{
    private $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    /**
     * @Route("/new", name="new_pet")
     */
    public function new(Request $request) : Response
    {
        // creates a pet and creates a form for adding new pet
        $form = $this->createForm(PetForm::class, $pet = new Pet());

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->petService->newPet($pet);
            return $this->redirectToRoute('new_pet');
        }
        return $this->render('pet/pet_new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/all", name="show_all")
     */
    public function showAll()
    {
        $pets = $this->petService->loadPets();
        return $this->render("pet/pet_show_all.html.twig", ['pets' => $pets]);
    }

    /**
     * @Route("/edit/{id}", name="edit_pet")
     */
    public function editPet($id, Request $request)
    {
        $pet = $this->petService->findById($id);
        $form = $this->createForm(PetForm::class, $pet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPhotos = $pet->getPhotos()->getSnapshot();
            $pet->setPhotos($pet->getPhotos()->unwrap());
            foreach ($oldPhotos as $photo) {
                $pet->addPhoto($photo);
            }

            $this->petService->saveChanges($pet);
            return $this->redirectToRoute('show_all');
        }

        return $this->render('pet/pet_new.html.twig', [
            'form' => $form->createView(), 'pet' => $pet
        ]);
    }

    /**
     * @Route("/deletePhoto/{photoName}", name="delete_photo")
     */
    public function deletePhoto(string $photoName)
    {
        return $this->petService->deletePhoto($photoName);
    }
}
