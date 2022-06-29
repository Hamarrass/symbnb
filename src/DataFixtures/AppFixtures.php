<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use Faker\Factory;  
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
       $this->encoder = $encoder ;  
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');
      
        //Nous gérons les utilisateurs
        // $users =[];
        $genres = ['male','demale'];
       
       
        for ($i=1 ; $i <=10 ; $i++) { 
            
           $user = new User();  
           
           $genre = $faker->randomElement($genres); 
           
           $picture ='https://randomuser.me/api/portraits/';
           $pictureId=$faker->numberBetween(1,99).'.jpg';

           $picture .= ($genre == 'male' ? 'men/' : 'women/' ).$pictureId;

           $hash = $this->encoder->encodePassword($user,'password');
      

           $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>',$faker->paragraphs()).'</p>')
                ->setHash($hash)
                ->setPicture($picture);

                $manager->persist($user);
                $users []=$user;
        }
        
        for ($i=1; $i <=30 ; $i++) {    

            $ad = new Ad();
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs()).'</p>';

            $user = $users[mt_rand(0,count($users)-1)];

            $ad->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(40,200))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user) ;
            

            for ($j=0; $j < mt_rand(2,5) ; $j++) { 

                 $image   = new Image();
                 $url     = $faker->imageUrl(640, 460, 'cats', true, 'Faker') ;
                 $caption = $faker->sentence();
                 $image->setAd($ad)
                       ->setUrl($url)
                       ->setCaption($caption) ;

                       $manager->persist($image);
                }

            $manager->persist($ad);
        }
        
        $manager->flush();
    }  
}
