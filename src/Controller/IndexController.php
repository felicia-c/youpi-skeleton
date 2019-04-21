<?php
namespace App\Controller;

use App\Entity\Element;

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

        if (!$elements) {
            throw $this->createNotFoundException(
                'No element found'
            );
        }
        return $this->render('index/index.html.twig', [
            'step' => 1,
            'elements' => $elements,
        ]);
    }

    /**
     * Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('login/login.html.twig', [
        'step' => 0,
        ]);
    }
}