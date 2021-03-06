<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    { 
        // $repo = $this->getDoctrine()->getRepository(Ad::class);
        $ads  = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }


    /**
     * Permet de créer une annonce
     *
     * @Route("/ads/new",name="ads_create")
     *
     * @return Response
     */
    
    Public function create(Request $req){
        $ad = new Ad();
    
        $form = $this->createForm(AdType::class,$ad); 
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager =$this->getDoctrine()->getManager();
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
               $manager->persist($image);
            }
        $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
                );

            return $this->redirectToRoute('ads_show',[
                'slug'=>$ad->getSlug()
            ]);

        }

  

        return $this->render('ad/new.html.twig',[
            'form' => $form->createView()
        ]); 
    }


     /**
      * Permet d'afficher le formulaire d'édition
      *
      * @Route("/ads/{slug}/edit",name="ads_edit")
      *
      * @return Response 
      */

      public function edit(Ad $ad , Request $req){

        $form = $this->createForm(AdType::class,$ad); 
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $manager =$this->getDoctrine()->getManager();
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
               $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "les Modifications de l'annonce  <strong>{$ad->getTitle()}</strong> ont  bien été enregistrée !"
                );

            return $this->redirectToRoute('ads_show',[
                'slug'=>$ad->getSlug()
            ]);

        }

          return $this->render('ad/edit.html.twig',[
              'form' => $form->createView(),
              'ad'=>$ad
            ]);
      }

   

    /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}",name="ads_show")
     * 
     * @return Reponse
     */

    public function show($slug , AdRepository  $repo , Ad $ad){

        // je recuperer l'annonce qui correspond au slug 
        // $ad = $repo->findOneBySlug($slug) ;

        return $this->render('ad/show.html.twig',[
            'ad' => $ad 
        ]) ;

    } 



}
