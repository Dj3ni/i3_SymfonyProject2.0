<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // I want  the name and firstname To be in ucFirst
            $user->setLastname(ucfirst($form->get('lastName')->getData()));
            $user->setFirstname(ucfirst($form->get('firstName')->getData()));

            // Avatar management if no Vich Bundle
            // /** @var UploadedFile $file */
            $file = $form->get("avatarFile")->getData();
            // dd($file);
            $fileName = "avatar.".$user->getLastname().".".random_int(0,1000).".".$file->getClientOriginalExtension();
            // dd($fileName);
            // dd($this->getParameter("kernel.project_dir"));
            $file->move($this->getParameter("kernel.project_dir")."/public/uploads",$fileName);
            $user->setAvatar($fileName);
            
            $entityManager->persist($user);
            $entityManager->flush();

            $user->setAvatarFile(null);

            // do anything else you need here, like send an email
            $this->addFlash("success", "Welcome young padawan, have fun!");
            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
