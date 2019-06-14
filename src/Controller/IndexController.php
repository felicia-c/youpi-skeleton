<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Creation;
use App\Entity\Element;
use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class IndexController extends AbstractController
{
    /**
     * Route("/", name="home")
     */
    public function index()
    {
        $elements = $this->getDoctrine()
            ->getRepository(Element::class)
            ->findAllPublished();

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllPublished();
        $creations = $this->getDoctrine()
            ->getRepository(Creation::class)
            ->findAllPublished();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$elements) {
            throw $this->createNotFoundException(
                'No element found'
            );
        }
        if (!$articles) {
            throw $this->createNotFoundException(
                'Aucun article trouvé'
            );
        }
        if (!$creations) {
            throw $this->createNotFoundException(
                'Aucune création trouvée'
            );
        }
        return $this->render('theme-a/index.html.twig', [
            'step' => 1,
            'elements' => $elements,
            'articles' => $articles,
            'creations' => $creations,
            'carousel' => $creations,
            'categories' => $categories,
        ]);
    }



/*
    public function blockArticles()
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        // $user = $this->getUser()
        $elements = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$elements) {
            throw $this->createNotFoundException(
                'No element found'
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('theme-a/pages/blog.html.twig', ['elements' => $elements,
            'page_title' => 'Mes articles']);
    }
*/
/*
    /**
     * Route("/login", name="login")
     */
/*
    public function login()
    {
        return $this->render('login/login.html.twig', [
        'step' => 0,
        ]);
    }
  */
    public function about() {
        $elements = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllPublished();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$elements) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }
        return $this->render('theme-a/pages/about.html.twig', [
            //'step' => 1,
            'page_title' => 'À propos',
            'about' => $elements,
            'categories' => $categories,
        ]);
    }

    public function creations() {
        //$category = new Category();
        $elements = $this->getDoctrine()
            ->getRepository(Creation::class)
           ->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAllPublished();


        if (!$elements) {
            throw $this->createNotFoundException(
                'No element found'
            );
        }
        return $this->render('theme-a/pages/creations.html.twig', [
            'page_title' => 'Créations',
            'creations' => $elements,
            'categories' => $categories,
        ]);
    }

    public function blog() {
        $elements = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllPublished();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$elements) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }
        return $this->render('theme-a/pages/blog.html.twig', [
            //'step' => 1,
            'page_title' => 'Blog',
            'elements' => $elements,
            'categories' => $categories,
        ]);
    }



    public function contact() {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
       /* $elements = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllPublished();

        if (!$elements) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }*/
        return $this->render('theme-a/pages/contact.html.twig', [
            //'step' => 1,
           // 'elements' => $elements,
            'categories' => $categories,
        ]);
    }

}