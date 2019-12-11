<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $faker = Faker\Factory::create();
            $article->setTitre($faker->text(50))
                ->setContent($faker->text(500))
                ->setImage("https://via.placeholder.com/640/300")
                ->setCreatedAt($faker->dateTime);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
