<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\EntityNotDeletedException;
use App\Form\UserForm;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/create/user", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createUser(Request $request): Response
    {
        $form = $this->createForm(UserForm::class, $user = new User());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->newUser($user);
            return $this->redirectToRoute('show_all');
        }

        return $this->render(
            'user/user.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/user/all", name="user_show_all")
     * @IsGranted("ROLE_ADMIN")
     */
    public function showAll(array $parameters = [])
    {
        return $this->render("user/user_show_all.html.twig", [
            'users' => $this->userService->getUsers(), 'parameters' => $parameters
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser($id, Request $request)
    {
        $user = $this->userService->findById($id);
        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->saveChanges($user);
            return $this->redirectToRoute('user_show_all');
        }

        return $this->render('user/user.html.twig', [
            'form' => $form->createView(), 'user' => $user
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(int $id)
    {
        try {
            $user = $this->userService->findById($id);
            $this->userService->deleteUser($user);
            return $this->redirectToRoute('user_show_all');
        } catch (EntityNotDeletedException $exception) {
            return $this->showAll(['message' => $exception->getMessage()]);
        }
    }
}
