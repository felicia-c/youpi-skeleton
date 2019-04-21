<?php
namespace App\Controller;

use App\Entity\Element;
use App\Form\ElementType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Service\FileUploader;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ElementController extends AbstractController
{

    /**
     * Route("/nouveau", name="nouveau")
     */
    public function addElement(Request $request,  FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $user = $this->getUser();

    /*
        $element = new Element();
        $element->setName($request->request->get('name'));
        $element->setInitDate(new \DateTime($request->request->get('initDate')));
        $element->setImage($request->request->get('image')
           // new File($this->getParameter('images_directory').'/'.$request->request->get('image'))
        );
        $form = $this->createFormBuilder($element)
            // ->add('name', TextType::class, ['label' => 'Nom'])
            // ->add('initDate', DateType::class, ['label' => 'Date'])
            //->add('save', SubmitType::class, ['label' => 'Étape suivante'])

            ->getForm();
*/

        $element = new Element();
        $form = $this->createForm(ElementType::class, $element);
        $element->setName($request->request->get('name'));
        $element->setInitDate(new \DateTime($request->request->get('initDate')));
        $element->setPublished(true);
        //$element->setImage($request->request->get('image'));
        //$element->setImage(new File($this->getParameter('images_directory').'/'.$fileName));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $element = $form->getData();

            $file = $element->getImage();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $element->setImage($fileName);

            } else {
                $element->setImage(null);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($element);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Nouvel élément ajouté !'
            );
            return $this->redirectToRoute('show_element', ['id' => $element->getId()]);
        }
        else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'warning',
                'Oops ! Le formulaire comporte des erreurs '
            );
        }


        return $this->render('form/create.html.twig', [
            'form' => $form->createView(),
           // 'image' => $element->getImage(),
            'button_text' => 'Valider',
            'step' => 2
        ]);
        /*

                return $this->render('form/create.html.twig', [
                    'step' => 2,
                ]);
        */
    }


    /**
     * Route("/element/{id}", name="element_show")
     */
    public function showElement($id)
    {
        $element = $this->getDoctrine()
            ->getRepository(Element::class)
            ->find($id);

        if (!$element) {
            throw $this->createNotFoundException(
                'No element found for id '.$id
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
         return $this->render('element/show.html.twig', ['element' => $element, 'published' => $element->getPublished() ]);
    }

    /**
     * Route("/element/unpublish/{id}", name="switch_publish_element")
     */
    public function switchPublishElement($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $user = $this->getUser();
        $element = $this->getDoctrine()
            ->getRepository(Element::class)
            ->find($id);

        $published = $element->getPublished();
       // var_dump($published);
        if ($published === true) {
            $element->setPublished(false);
        } else {
            $element->setPublished(true);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();


        if (!$element) {
            throw $this->createNotFoundException(
                'No element found for id '.$id
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        //return $this->render('element/show.html.twig', ['element' => $element, 'name' => $element->getName(), 'initDate' => $element->getInitDate(), 'image' => $element->getImage()  ]);
        return $this->listElements();
    }

    /**
     * Route("/element/edit/{id}", name="edit_element")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editElement($id, Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        //$user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Element::class)->find($id);
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

        $form = $this->createForm(ElementType::class, $element);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $element->getImage();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $element->setImage($fileName);

            } else {
                $element->setImage($oldFileName);
            }

            /** @var Element $element */
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Élément modifié !');
            return $this->redirectToRoute('show_element', [
                'id' => $element->getId()
            ]);
        }
        return $this->render('form/create.html.twig', [
            'form' => $form->createView(),
            //'miniature' => $oldFileNamePath,
            'id' => $element->getId(),
            'image' => $oldFileName,
            'edit' => true,
            //'image' => $element->getImage(),
            'button_text' => 'Modifier !',
        ]);
    }


    /**
     * Route("/element/delete/{id}", name="delete_element")
     */
    public function deleteElement($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Element::class)->find($id);

        if (!$element) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $entityManager->remove($element);
        $entityManager->flush();

        return $this->redirectToRoute('list_element');
    }


    /**
     * Route("/elements", name="element_list")
     */
    public function listElements()
    {
       // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
       // $user = $this->getUser()
        $elements = $this->getDoctrine()
            ->getRepository(Element::class)
            ->findAll();

        if (!$elements) {
            throw $this->createNotFoundException(
                'No element found'
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('pages/elements-list.html.twig', ['elements' => $elements]);
    }
}