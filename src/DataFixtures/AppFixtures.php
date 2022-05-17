<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;  
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');
        
        for ($i=1; $i <=30 ; $i++) {    

            $ad = new Ad();
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs()).'</p>';


            $ad->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(40,200))
            ->setRooms(mt_rand(1,5)) ;
            

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
