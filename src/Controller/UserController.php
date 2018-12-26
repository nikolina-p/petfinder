<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserForm;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    /**
     * @Route("/create/user", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createUser(Request $request, UserService $userService): Response
    {
        $form = $this->createForm(UserForm::class, $user = new User());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->newUser($user);
            return $this->redirectToRoute('show_all');
        }

        return $this->render(
            'user/user.html.twig',
            ['form' => $form->createView()]
        );
    }
}
