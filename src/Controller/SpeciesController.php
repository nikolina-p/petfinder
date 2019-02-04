<?php

namespace App\Controller;

use App\Entity\Species;
use App\Service\SpeciesService;
use App\Form\SpeciesForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\EntityNotDeletedException;
use Symfony\Component\HttpFoundation\Response;

class SpeciesController extends AbstractController
{
    private $speciesService;

    public function __construct(SpeciesService $speciesService)
    {
        $this->speciesService = $speciesService;
    }

    /**
     * @Route("/species", name="species_show_all")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(): Response
    {
        $species = $this->speciesService->loadSpecies();
        return $this->render('species/index.html.twig', [
            'species' => $species
        ]);
    }

    /**
     * @Route("/species/new", name="new_species")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function newSpecies(Request $request): Response
    {
        $form = $this->createForm(SpeciesForm::class, $species = new Species());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->speciesService->newSpecies($species);
            return $this->redirectToRoute('species_show_all');
        }
        return $this->render('species/species_new.html.twig', array(
            'form' => $form->createView(), 'species' => $species
        ));
    }

    /**
     * @Route("/species/edit/{id}", name="edit_species")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editSpecies(Request $request, Species $species): Response
    {
        $form = $this->createForm(SpeciesForm::class, $species);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->speciesService->editSpecies($species);
            return $this->redirectToRoute('species_show_all');
        }
        return $this->render('species/species_new.html.twig', array(
            'form' => $form->createView(), 'species' => $species
        ));
    }

    /**
     * @Route("/species/delete/{id}", name="delete_species")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteSpecies(Request $request, Species $species): Response
    {
        try {
            $this->speciesService->deleteSpecies($species);
            return new Response(null, 204);
        } catch (EntityNotDeletedException $exception) {
            return new Response($exception->getMessage(), 400);
        }
    }
}
