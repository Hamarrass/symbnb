<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

 class HomeController extends AbstractController {


  /**
   * @Route("/hello/{prenom}/age/{age}",name="hello")
   * @Route("/hello",name="hello_base")
   * @Route("/hello/{prenom}",name="hello_prenom")
   * Montre la page qui dit bonjour 
   * @return void 
   */

   public function hello($prenom="anonym",$age=" 0 "){

     return $this->render('hello.html.twig',['age'=>$age, 'prenom'=>$prenom]) ;

   }

    /**
     * @Route("/",name="homepage")
     */
   public function home(){
 
       $prenom = ["hassan","jamal","kamal","Tata"];

          return  $this->render(
            'home.html.twig' , ['title'=>"Bonjour à tous" , 'age'=>5,'prenom'=>$prenom]
          );
          
   }

 }

?> 