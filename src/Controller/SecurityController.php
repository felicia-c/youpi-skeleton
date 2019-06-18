<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('theme-a/pages/login.html.twig', [
            'site' => $siteInfos,
            'categories' => $categories,
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Connexion'
        ]);
    }
}
