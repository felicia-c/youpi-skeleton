<?php

namespace App\Controller;

use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use App\Service\FileUploader;


use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;


use App\Form\ElementType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SiteController extends AbstractController
{
    /**
     * Route("/admin/site-infos", name="show_site_infos")
     */
    public function showSiteInfos()
    {
        //$siteInfos = new Site();
        $siteInfos = $this->getDoctrine()
        ->getRepository(Site::class)
        ->find(1);
/*
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
*/
        if (!$siteInfos) {
            throw $this->createNotFoundException(
                'No informations found'
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('theme-a/admin/site-infos.html.twig', [
            'site' => $siteInfos,
            'page_title' => 'Informations du site'
            //'categories' => $categories,
            //'published' => $element->getPublished()
        ]);
    }



    /**
     * @Route("/site", name="site")
     */

/*
    public function addSiteInfos()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Ah zut ! Vous n\'avez pas accès à cette page');
        $user = $this->getUser();
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->findAll();
        //$element = new Creation();
        //$category = new Category();
        $form = $this->createForm(CreationType::class, $element);
        $element->setTitle($request->request->get('title'));
        $category->setName($form->get('category')->getData());
        $element->setAchievementDate(new \DateTime($request->request->get('achievementDate')));
        $element->setPublished(true);
        //$element->setImage($request->request->get('image'));
        //$element->setImage(new File($this->getParameter('images_directory').'/'.$fileName));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$category->setName($form->get('category'));
            //$entityObject = $form->get('category')->getData();
            //$category = $category->get('Id');
            $element = $form->getData();
            $element->addCategory($category);

            $file = new UploadedFile($element->getImage(), $element->getImage());
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $element->setImage($fileName);

            } else {
                $element->setImage(null);
            }

            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($category);
            $entityManager->persist($element);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Nouvelle informations validées !'
            );

            return $this->redirectToRoute('list_creations');
            //return $this->redirectToRoute('creations');
        }
        else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'warning',
                'Oops ! Le formulaire comporte des erreurs '
            );
        }


        return $this->render('theme-a/admin/siteInfos.html.twig', [
            'form' => $form->createView(),
            //'categories' => $categories,
            // 'image' => $element->getImage(),
            'button_text' => 'Valider',
            'step' => 2,
            'page_title' => 'Informations du site'
        ]);

    }
*/


    /**
     * Route("/site/edit/{id}", name="edit_site_infos")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editSiteInfos(Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        //$user = $this->getUser();
        /*$categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        */
        $siteInfosActual = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);


        $entityManager = $this->getDoctrine()->getManager();
        $siteInfos = $entityManager->getRepository(Site::class)->find(1);
        if (!$siteInfos) {
            throw $this->createNotFoundException(
                'No site infos found'
            );
        }
        // we must transform the image string from Db  to File to respect the form types
        if ( $siteInfos->getLogo() !== null) {
            $oldFileName = $siteInfos->getLogo();
        } else {
            $oldFileName = null;
        }
        if ( $siteInfos->getHeaderBgImage() !== null) {
            $oldFileName_headerBgImage = $siteInfos->getHeaderBgImage();
        } else {
            $oldFileName_headerBgImage = null;
        }


        //$oldFileNamePath = $this->getParameter('images_directory').'/'.$oldFileName;
        //$pictureFile = new File($this->getParameter('brochures_directory').'/'.$element->getImage());
        if ($oldFileName != null ) {
            $siteInfos->setLogo(new File($this->getParameter('images_directory').'/'.$oldFileName));
        } else {
            $siteInfos->setLogo(null);
        }
        if ($oldFileName_headerBgImage != null ) {
            $siteInfos->setHeaderBgImage(new File($this->getParameter('images_directory').'/'.$oldFileName_headerBgImage));
        } else {
            $siteInfos->setHeaderBgImage(null);
        }

        $form = $this->createForm(SiteType::class, $siteInfos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $siteInfos->getLogo();
            $file_headerBgImage = $siteInfos->getHeaderBgImage();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $siteInfos->setLogo($fileName);

            } else {
                $siteInfos->setLogo($oldFileName);
            }

            if ($file_headerBgImage) {
                $fileName_headerBgImage = $fileUploader->upload($file_headerBgImage);
                $siteInfos->setHeaderBgImage($fileName_headerBgImage);

            } else {
                $siteInfos->setHeaderBgImage($oldFileName_headerBgImage);
            }

            /** @var Creation $siteInfos */
            $siteInfos = $form->getData();
            $entityManager->persist($siteInfos);
            $entityManager->flush();
            $this->addFlash('success', 'Informations du site modifiées !');
            return $this->redirectToRoute('show_site_infos', [
                //'id' => $siteInfos->getId(),
                'page_title' => 'Informations du site'
            ]);
        }
        return $this->render('theme-a/admin/edit-infos.html.twig', [

            'form' => $form->createView(),
            'site' => $siteInfosActual,
            //'miniature' => $oldFileNamePath,
            //'id' => $element->getId(),
            'logo' => $oldFileName,
            'header_bgImage' => $oldFileName_headerBgImage,
            //'categories' => $categories,
            'edit' => true,
            //'image' => $element->getImage(),
            'button_text' => 'Modifier !',
            'page_title' => 'Modifier les informations'
        ]);
    }

}
