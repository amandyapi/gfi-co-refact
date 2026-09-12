<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateurs', name: 'app_admin_user_')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAllOrdered(),
        ]);
    }

    #[Route('/nouveau', name: 'new')]
    public function new(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod('POST')) {
            $data = $this->validateUserRequest($request, true);

            if (empty($data['errors'])) {
                $user = new User();
                $this->hydrateUser($user, $data['values'], $hasher, true);

                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash('success', 'Utilisateur créé avec succès.');

                return $this->redirectToRoute('app_admin_user_index');
            }

            return $this->render('admin/user/form.html.twig', [
                'isEdit' => false,
                'data' => $data['values'],
                'errors' => $data['errors'],
            ]);
        }

        return $this->render('admin/user/form.html.twig', [
            'isEdit' => false,
            'data' => [
                'firstname' => '',
                'lastname' => '',
                'email' => '',
                'roles' => [User::ROLE_USER],
                'isActive' => true,
            ],
            'errors' => [],
        ]);
    }

    #[Route('/{id}/editer', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod('POST')) {
            $data = $this->validateUserRequest($request, false);

            if (empty($data['errors'])) {
                $this->hydrateUser($user, $data['values'], $hasher, false);
                $this->em->flush();
                $this->addFlash('success', 'Utilisateur modifié avec succès.');

                return $this->redirectToRoute('app_admin_user_index');
            }

            return $this->render('admin/user/form.html.twig', [
                'isEdit' => true,
                'user' => $user,
                'data' => $data['values'],
                'errors' => $data['errors'],
            ]);
        }

        return $this->render('admin/user/form.html.twig', [
            'isEdit' => true,
            'user' => $user,
            'data' => [
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'isActive' => $user->isActive(),
            ],
            'errors' => [],
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
            if ($user === $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            } else {
                $this->em->remove($user);
                $this->em->flush();
                $this->addFlash('success', 'Utilisateur supprimé avec succès.');
            }
        }

        return $this->redirectToRoute('app_admin_user_index');
    }

    // =====================================================
    // Helpers
    // =====================================================

    private function validateUserRequest(Request $request, bool $isCreation): array
    {
        $values = [
            'firstname'     => trim((string) $request->request->get('firstname', '')),
            'lastname'      => trim((string) $request->request->get('lastname', '')),
            'email'         => trim((string) $request->request->get('email', '')),
            'roles'         => (array) $request->request->all('roles'),
            'isActive'      => (bool) $request->request->get('isActive', false),
            'plainPassword' => (string) $request->request->get('plainPassword', ''),
        ];

        $errors = [];

        if ($values['firstname'] === '') $errors['firstname'] = 'Le prénom est obligatoire.';
        if ($values['lastname'] === '')  $errors['lastname']  = 'Le nom est obligatoire.';
        if ($values['email'] === '')     $errors['email']     = "L'email est obligatoire.";
        elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide.";
        }

        if (empty($values['roles'])) {
            $errors['roles'] = 'Sélectionnez au moins un rôle.';
        }

        if ($isCreation && strlen($values['plainPassword']) < 6) {
            $errors['plainPassword'] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        return ['values' => $values, 'errors' => $errors];
    }

    private function hydrateUser(User $user, array $values, UserPasswordHasherInterface $hasher, bool $isCreation): void
    {
        $user->setFirstname($values['firstname']);
        $user->setLastname($values['lastname']);
        $user->setEmail($values['email']);
        $user->setRoles($values['roles']);
        $user->setIsActive($values['isActive']);

        if (!empty($values['plainPassword'])) {
            $user->setPassword($hasher->hashPassword($user, $values['plainPassword']));
        }
    }
}