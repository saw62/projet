<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo) // l'injection de dependance

    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', ['art' => $articles]);
    }

    /**
     *@Route("/blog/delete/{id}", name="blog_delete")
     */

    public function delete($id, Article $article = null, Request $request, EntityManagerInterface $manager, ArticleRepository $repo)
    {
        $article = $repo->find($id);
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('blog');
    }

    /**
     *@Route("/blog/new", name="blog_create")
     *@Route("/blog/{id}/edit", name="blog_edit")
     */

    function new (Article $article = null, Request $request, EntityManagerInterface $manager) {

        if (!$article) {
            $article = new Article();
            $art = false;
        } else {
            $art = true;
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $article->setCreatedAt(new \Datetime());
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToroute('article', ['id' => $article->getId()]);
        }
        return $this->render("blog/new.html.twig",
            [
                "form" => $form->createView(),
                "art" => $art,
                "article" => $article,
            ]);

    }

    /**
     * @Route("/blog/{id}", name="article")
     */
    public function one(Article $article) // la magie du param converter

    {
        return $this->render('blog/article.html.twig',
            [
                'article' => $article,
            ]);
    }

    /**
     * @Route("/", name="home")
     */

    public function home()
    {
        return $this->render('blog/home.html.twig');
    }
}
