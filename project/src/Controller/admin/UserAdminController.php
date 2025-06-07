<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/user')]
final class UserAdminController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    /**
     * Wyświetla listę wszystkich użytkowników w systemie.
     * Obsługuje wyszukiwanie użytkowników.
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $searchTerm = $request->query->get('search');

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findBySearchTerm($searchTerm),
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    /**
     * Tworzy nowego użytkownika w systemie.
     * Obsługuje formularz tworzenia użytkownika i zapisuje go w bazie danych.
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    /**
     * Wyświetla szczegóły wybranego użytkownika.
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    /**
     * Edytuje istniejącego użytkownika.
     * Obsługuje formularz edycji i aktualizuje dane w bazie.
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    /**
     * Usuwa użytkownika z systemu.
     * Weryfikuje token CSRF przed wykonaniem operacji.
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
