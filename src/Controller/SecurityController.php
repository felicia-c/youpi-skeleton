<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Site;
use App\Repository\UserRepository;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(){
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/forgotten_password", name="forgotten_password")
     */

    public function forgottenPassword(
        Request $request,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByUserEmail($email);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'E-mail Inconnu');
                return $this->redirectToRoute('forgotten_password');
            }
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('index');
            }
            $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom($siteInfos->getSiteContact())
                ->setTo($user->getUserEmail())
                ->setBody(
                    "Bonjour, <br> Vous avez demandé un changement de mot-de-passe, <b>pour réinitialiser votre mot de passe, cliquez ici : " . $url ." </b><br><br> Si vous n'êtes pas à l'originie de cette demande, veuillez ne pas tenir compte de ce message. ",
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('success', 'E-mail envoyé');
            return $this->redirectToRoute('app_login');
        }
        //return $this->render('security/forgotten_password.html.twig');
        return $this->render('theme-a/security/forgot.html.twig', [
            //'form' => $form->createView(),
            'site' => $siteInfos,
            'categories' => $categories,
            'step_title' => 'Connexion',
            'step_path' => 'app_login',
            'page_title' => 'Mot de passe oublié',
        ]);
    }
/*
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {

        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */
/*
            if ($user === null || $email === '') {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('index');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('index');
            }

            $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);


            $message = (new \Swift_Message('Réinitialiser votre mot de passe'))
                ->setFrom('felicia.cuneo@gmail.com')
                ->setTo($user->getUserEmail())
                ->setBody(
                    "Pour réinitialiser votre mot de passe, cliquez ici : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('login');
        }

        return $this->render('theme-a/security/forgot.html.twig', [
            //'form' => $form->createView(),
            'site' => $siteInfos,
            'categories' => $categories,
            'page_title' => 'Mot de passe oublié',
        ]);
    }
*/
    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
        /* @var $user User */

        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('index');
        }
        $formNewPassword = $this->createForm(ChangePasswordType::class, $user);
        $formNewPassword->handleRequest($request);
       // if ($request->isMethod('POST')) {
        if ($formNewPassword->isSubmitted() && $formNewPassword->isValid()) {
            $user->setResetToken(null);
            //$user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $formNewPassword->get('password')->getData()
                )
            );

            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe mis à jour !');

            return $this->redirectToRoute('login');
        }else {

            return $this->render('theme-a/security/reset-password.html.twig', [
                'token' => $token,
                'form' => $formNewPassword->createView(),
                'site' => $siteInfos,
                'categories' => $categories,
                'page_title' => 'Réinitialiser le mot de passe',

            ]);
        }

    }
}
