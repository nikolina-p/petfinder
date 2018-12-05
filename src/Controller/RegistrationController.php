<?php

namespace App\Controller;

use App\Form\RegistrationForm;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserService $userService): Response
    {
        // 1) build the form
        $form = $this->createForm(RegistrationForm::class, $user = new User());

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $userService->encodePassword($user);
            $user->setPassword($password);

            $userService->persist($user);

            return $this->redirectToRoute('user_registration');
        }

        return $this->render(
            'registration/register.html.twig',
            ['form' => $form->createView()]
        );
    }

}