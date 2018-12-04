<?php

namespace App\Controller;

use App\Entity\Pet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PetForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PetController extends AbstractController
{
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

            // save the Pet!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pet);
            $entityManager->flush();

            return $this->redirectToRoute('new_pet');
        }
        return $this->render('pet/pet.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}