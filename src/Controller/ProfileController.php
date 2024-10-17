<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use App\Form\ChangePasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("profile")
 */
class ProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="app_profile_show", methods={"GET"})
     */
    public function profileOverview(): Response
    {
        $user = $this->security->getUser();

        // Check if the user is authenticated
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('You are not authenticated.');
        }

        
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/update", name="app_profile_update", methods={"GET", "POST"})
     */
    public function updateProfile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->security->getUser();

        // Check if the user is authenticated
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('You are not authenticated.');
        }


        $form = $this->createForm(UserType::class, $user,[
            'include_password' => false, // Vous pouvez passer une option pour contrôler cela
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/change-password", name="app_profile_change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
    
        // Vérifiez que l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour changer votre mot de passe.');
        }
    
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
    
            // Vérifiez le mot de passe actuel
            if ($userPasswordHasher->isPasswordValid($user, $data['current_password'])) {
                // Hachez le nouveau mot de passe et mettez à jour l'utilisateur
                $newPassword = $userPasswordHasher->hashPassword($user, $data['new_password']);
                $user->setPassword($newPassword);
    
                // Enregistrer les changements
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
    
                return $this->redirectToRoute('app_logout'); // Redirigez vers une page appropriée
            } else {
                $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
            }
        }
    
        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
