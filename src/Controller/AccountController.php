<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils  $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */

    public function logout(){
        // .. rien !
    }


    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/registre",name="account_register")
     * 
     * @return Response
     */

     public function register(Request $request , UserPasswordEncoderInterface $encoder){
         $user= new User();
         $form = $this->createForm(RegistrationType::class , $user);
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){
             $hash= $encoder->encodePassword($user,$user->getHash());
             $user->setHash($hash);
            $manager =$this->getDoctrine()->getManager();
             $manager->persist($user);
             $manager->flush();
             $this->addFlash('success','Votre compte a bien été créé ! vous pouvez maintenant vous connecter !');
             return $this->redirectToRoute('account_login');
              
         }
         return $this->render('account/registration.html.twig',[
            'form'=>$form->createView() 
         ]);  
     }


     /**
      * Permet d'afficher et de traiter le formulaire de modification de profil
      *
      *@Route("/account/profile",name="account_profile")
      *
      *@return Response
      */
      public function profile(Request $request){
          $user =$this->getUser();
          $form = $this->createForm(AccountType::class,$user);

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()){
             $manager =$this->getDoctrine()->getManager();
             $manager->persist($user);
             $manager->flush();

             $this->addFlash(
                 'success',
                 "Les données du profil ont été enregistrée avec succès !"
             );
          }

          return $this->render('account/profile.html.twig',[
              'form'=>$form->createView()
          ]);
      }

     /**
      * Permet de modifier le mot de passe
      *
      * @Route("/acount/password-update",name="account_password")
      *
      * @return Response
      */

      public function updatePassword(Request $request , UserPasswordEncoderInterface $encoder){
           $passwordUpdate = new PasswordUpdate();
           $user = $this->getUser();
           $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);

           $form->handleRequest($request);

           if($form->isSubmitted() && $form->isValid()){
               //1.Vérifier que le OldPassword du formulaire eoit le meme que password de l'user
               if(!password_verify($passwordUpdate->getOldPassword() ,$user->getHash())){
                  $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actual !"));
               }

               else {
                   $newPassword = $passwordUpdate->getNewPassword();
                   $hash        = $encoder->encodePassword($user,$newPassword); 

                   $user->setHash($hash);
                   $manager =$this->getDoctrine()->getManager();

                   $manager->persist($user);
                   $manager->flush();

                   $this->addFlash("success","Votre mot de passe a bien été modifié") ;

                   return $this->redirectToRoute('homepage');
               }
           }



          return $this->render('account/password.html.twig',[
              'form'=>$form->createView()
            ]);
      }

      /**
       * ¨Permet d'afficher le profil de l'utilisateur connecté
       * 
       * @Route("/account",name="account_index")
       * 
       * @return Response
       */

       public function myAcount(){
           return $this->render('user/index.html.twig',[
               'user'=>$this->getUser()
            ]);
       }

}
