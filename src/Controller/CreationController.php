<?php
namespace App\Controller;

use App\Entity\Creation;
use App\Entity\Element;
use App\Entity\Category;

use App\Form\CreationType;
use App\Form\ElementType;


use App\Repository\CreationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Service\FileUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreationController extends AbstractController
{

    /**
     * Route("/admin/nouvelle-creation", name="new_creation")
     */
    public function addCreation(Request $request,  FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Ah zut ! Vous n\'avez pas accès à cette page');
        $user = $this->getUser();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $element = new Creation();
        $category = new Category();
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
                'Nouvelle création ajoutée !'
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


        return $this->render('theme-a/admin/new-creation.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            // 'image' => $element->getImage(),
            'button_text' => 'Valider',
            'step' => 2,
            'page_title' => 'Nouvelle création'
        ]);
        /*

                return $this->render('form/create.html.twig', [
                    'step' => 2,
                ]);
        */
    }


    /**
     * Route("/creations/{id}", name="show_creation")
     */
    public function showCreation($id)
    {
        $element = $this->getDoctrine()
            ->getRepository(Creation::class)
            ->find($id);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$element) {
            throw $this->createNotFoundException(
                'Impossible de trouver la création n°'.$id
            );
        }
        //$categoryName = $element->getCategory()->getName();
        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('theme-a/pages/show-creation.html.twig', [
            'creation' => $element,
            'categories' => $categories,
            'published' => $element->getPublished(),
            'page_title' => $element->getTitle(),
            //'category' => $categoryName,
            'step_title' => 'Créations'
        ]);
    }


    /**
     * Route("/creations/$name", name="show_creations_category")
     */
    public function showCreationsCategory(CreationRepository $repo, $name)
    {
        //$category = new Category();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(array('name' => $name));
        $id = $category->getId();
        $elements = $repo->findByCategory($name);

        $name = $category->getName();
        if (!$category) {
            throw $this->createNotFoundException(
                'Impossible de trouver la categorie '.$name
            );
        }
        if (!$elements) {
            throw $this->createNotFoundException(
                'Impossible de trouver les creations dans  '.$name
            );
        }
        //$categoryName = $element->getCategory()->getName();
        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('theme-a/pages/creations.html.twig', [
            'categories' => $categories,
            'category' => $category,
            'creations' => $elements,
            //'published' => $elements->getPublished(),
           // 'published' => $element->getPublished(),
            'page_title' => $name,
            //'category' => $categoryName,
            'step_title' => 'Créations'
        ]);
    }



    /**
     * Route("/creation/unpublish/{id}", name="switch_publish_creation")
     */
    public function switchPublishCreation($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $user = $this->getUser();
        $element = $this->getDoctrine()
            ->getRepository(Creation::class)
            ->find($id);

        $published = $element->getPublished();
        // var_dump($published);
        if ($published === true) {
            $element->setPublished(false);
            $flashText = 'Élément dépublié !';
            $flashType = 'warning';
        } else {
            $element->setPublished(true);
            $flashText = 'Élément publié !';
            $flashType = 'success';
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();


        if (!$element) {
            throw $this->createNotFoundException(
                'No element found for id '.$id
            );
        }
        $this->addFlash($flashType, $flashText);
        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        //return $this->render('element/show.html.twig', ['element' => $element, 'name' => $element->getName(), 'initDate' => $element->getInitDate(), 'image' => $element->getImage()  ]);
        return $this->listCreations();
    }

    /**
     * Route("/creation/edit/{id}", name="edit_creation")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editCreation($id, Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        //$user = $this->getUser();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Creation::class)->find($id);
        if (!$element) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        // we must transform the image string from Db  to File to respect the form types
        $oldFileName = $element->getImage();

        //$oldFileNamePath = $this->getParameter('images_directory').'/'.$oldFileName;
        //$pictureFile = new File($this->getParameter('brochures_directory').'/'.$element->getImage());
        if ($oldFileName != null ) {
            $element->setImage(new File($this->getParameter('images_directory').'/'.$oldFileName));
        } else {
            $element->setImage(null);
        }

        $form = $this->createForm(CreationType::class, $element);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $element->getImage();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $element->setImage($fileName);

            } else {
                $element->setImage($oldFileName);
            }

            /** @var Creation $element */
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Élément modifié !');
            return $this->redirectToRoute('list_creations', [
                'id' => $element->getId(),
                'page_title' => 'Mes créations'
            ]);
        }
        return $this->render('theme-a/admin/new-creation.html.twig', [
            'form' => $form->createView(),
            //'miniature' => $oldFileNamePath,
            'id' => $element->getId(),
            'image' => $oldFileName,
            'categories' => $categories,
            'edit' => true,
            //'image' => $element->getImage(),
            'button_text' => 'Modifier !',
            'page_title' => 'Modifier création'
        ]);
    }


    /**
     * Route("/admin/creation/delete/{id}", name="delete_creation")
     */
    public function deleteCreation($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Creation::class)->find($id);

        if (!$element) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $entityManager->remove($element);
        $entityManager->flush();
        $this->addFlash('error', 'Élément supprimé !');
        return $this->redirectToRoute('list_creations');
    }


    /**
     * Route("/admin/mes-creations", name="list_creations")
     */
    public function listCreations()
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        // $user = $this->getUser()
        $elements = $this->getDoctrine()
            ->getRepository(Creation::class)
            ->findAll();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$elements) {
            throw $this->createNotFoundException(
                'No element found'
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
       // return $this->render('theme-a/pages/elements-list.html.twig', ['elements' => $elements]);
        return $this->render('theme-a/admin/list.html.twig', [
            'elements' => $elements,
            'categories' => $categories,
            'page_title' => 'Mes créations',
            'edit_path' => 'edit_creation',
            'publish_path' => 'switch_publish_creation',
            'delete_path' => 'delete_creation',
        ]);
    }
}