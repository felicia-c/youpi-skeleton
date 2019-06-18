<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Site;
use App\Entity\Category;
use App\Form\ServiceType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends AbstractController
{


    /**
     * Route("/admin/site-infos", name="show_site_infos")
     */
    public function listServices()
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);

        $services = $this->getDoctrine()
            ->getRepository(Service::class)
            ->findAll();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$siteInfos) {
            throw $this->createNotFoundException(
                'No informations found'
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('theme-a/admin/list-services.html.twig', [
            'site' => $siteInfos,
            'page_title' => 'Mes services',
            'categories' => $categories,
            'elements' => $services,
            'edit_path' => 'edit_service',
            //'publish_path' => 'switch_publish_creation',
            'delete_path' => 'delete_service',
            //'published' => $element->getPublished()
        ]);
    }

    /**
     * Route("/admin/nouveau-service", name="new_service")
     */
    public function addService(Request $request,  FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Ah zut ! Vous n\'avez pas accès à cette page');
        $user = $this->getUser();
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAllPublished();
        $element = new Service();
        //$category = new Category();
        $form = $this->createForm(ServiceType::class, $element);
        //$element->setTitle($request->request->get('title'));
        //$category->setName($form->get('category')->getData());
        //$element->setAchievementDate(new \DateTime($request->request->get('achievementDate')));
        //$element->setPublished(true);
        //$element->setImage($request->request->get('image'));
        //$element->setImage(new File($this->getParameter('images_directory').'/'.$fileName));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$category->setName($form->get('category'));
            //$entityObject = $form->get('category')->getData();
            //$category = $category->get('Id');
            $element = $form->getData();
            //$element->addCategory($category);

            /*if ($oldFileName !== null ) {
                $element->setImageHome(new File($this->getParameter('images_directory').'/'.$oldFileName));
            } else {
                $element->setImageHome(null);
            } */
            $file = false;
            if ($element->getImageHome() !== null){
                $file = new UploadedFile($element->getImageHome(), $element->getImageHome());
            }
            if ($file ) {
                $fileName = $fileUploader->upload($file);
                $element->setImageHome($fileName);

            } else {
                $element->setImageHome(null);
            }

            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($category);
            $entityManager->persist($element);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Nouveau service ajouté !'
            );

            return $this->redirectToRoute('list_services');
            //return $this->redirectToRoute('creations');
        }
        else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'warning',
                'Oops ! Le formulaire comporte des erreurs '
            );
        }


        return $this->render('theme-a/admin/new-service.html.twig', [
            'site' => $siteInfos,
            'form' => $form->createView(),
            'categories' => $categories,
            // 'image' => $element->getImage(),
            'button_text' => 'Valider',
            'step' => 2,
            'page_title' => 'Nouveau service'
        ]);
        /*

                return $this->render('form/create.html.twig', [
                    'step' => 2,
                ]);
        */
    }


    /**
     * Route("/admin/services/edit/{id}", name="edit_service")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editService($id, Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        //$user = $this->getUser();
        /*$categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        */
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);

        $entityManager = $this->getDoctrine()->getManager();

        $service = $entityManager->getRepository(Service::class)->find($id);
        if (!$service ) {
            throw $this->createNotFoundException(
                'No site infos found'
            );
        }
        // we must transform the image string from Db  to File to respect the form types
      /*  if ( $service->getImageHome() !== null) {
            $oldFileName = $service->getImageHome();
        } else {
            $oldFileName = null;
        }
*/
        $oldFileName = $service->getImageHome();
        //$oldFileNamePath = $this->getParameter('images_directory').'/'.$oldFileName;
        //$pictureFile = new File($this->getParameter('brochures_directory').'/'.$element->getImage());
        if ($oldFileName !== null ) {
            $service->setImageHome(new File($this->getParameter('images_directory').'/'.$oldFileName));
        } else {
            $service->setImageHome(null);
        }

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $service->getImageHome();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $service->setImageHome($fileName);

            } else {
                $service->setImageHome($oldFileName);
            }

            /** @var Servicen $service */
            $service = $form->getData();
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash('success', 'Informations du site modifiées !');
            return $this->redirectToRoute('list_services', [
                //'id' => $siteInfos->getId(),
                'page_title' => 'Informations du site',
                'site' => $siteInfos,
            ]);
        }
        return $this->render('theme-a/admin/new-service.html.twig', [

            'form' => $form->createView(),
            'site' => $siteInfos,
            //'miniature' => $oldFileNamePath,
            //'id' => $element->getId(),
            //'logo' => $oldFileName,
            'image_home' => $oldFileName,
            //'categories' => $categories,
            'edit' => true,
            //'image' => $element->getImage(),
            'button_text' => 'Modifier !',
            'page_title' => 'Modifier un élément'
        ]);
    }

    /**
     * Route("/admin/service/delete/{id}", name="delete_service")
     */
    public function deleteService($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Service::class)->find($id);

        if (!$element) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $entityManager->remove($element);
        $entityManager->flush();
        $this->addFlash('error', 'Élément supprimé !');
        return $this->redirectToRoute('list_services');
    }

}
