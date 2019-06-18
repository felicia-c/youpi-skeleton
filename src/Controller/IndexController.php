<?php
namespace App\Controller;

use App\Entity\Site;
use App\Entity\Article;
use App\Entity\Creation;
use App\Entity\Element;
use App\Entity\Category;
use App\Form\ContactType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class IndexController extends AbstractController
{

    /**
     * Route("/", name="home")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
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

        $formContact = $this->createForm(ContactType::class);
        $formContact->handleRequest($request);

           if ($formContact->isSubmitted() && $formContact->isValid()) {

               $contactFormData = $formContact->getData();

               dump($contactFormData);
               $message = (new \Swift_Message('Un nouveau message ! Youpi !'))
                   ->setFrom($contactFormData['from'])
                   ->setTo('adrienrogard@gmail.com')
                   ->setBody(
                       //$contactFormData['name'],
                       $contactFormData['message'],
                       'text/plain'
                   );

               $mailer->send($message);
               $this->addFlash('success', 'Votre message a été envoyé !');

               return $this->redirectToRoute('index');

           }
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
            'site' => $siteInfos,
            'elements' => $elements,
            'articles' => $articles,
            'creations' => $creations,
            'carousel' => $creations,
            'categories' => $categories,
            'contact' => $formContact->createView(),
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
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
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
            'site' => $siteInfos,
            'about' => $elements,
            'categories' => $categories,
        ]);
    }

    public function creations() {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
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
            'site' => $siteInfos,
            'creations' => $elements,
            'categories' => $categories,
        ]);
    }

    public function blog() {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
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
            'site' => $siteInfos,
            'elements' => $elements,
            'categories' => $categories,
        ]);
    }



    public function contact() {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
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
            'site' => $siteInfos,
            'categories' => $categories,
        ]);
    }

}