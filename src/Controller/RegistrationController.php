<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Role;
use App\Entity\Specialist;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserLoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserLoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $userRoleStr = $form->get('roles')->getData();

            $userRole = $entityManager->getRepository(Role::class)->findOneBy(['roleName' => $userRoleStr]);

            $user->addRole($userRole);

            if (in_array('ROLE_USER', $user->getRoles(), true)) {
                $client = new Client();
                $client->setUserId($user);
                $entityManager->persist($client);
            } elseif (in_array('ROLE_SPECIALIST', $user->getRoles(), true)) {
                $specialist = new Specialist();
                $specialist->setUserId($user);
                $entityManager->persist($specialist);
            }

            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $user->setUpdatedAt(new \DateTimeImmutable('now'));

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
