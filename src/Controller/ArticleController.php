<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;

use App\Form\ArticleType;

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


class ArticleController extends AbstractController
{
    /**
     * Route("/admin/nouvel-article", name="new-article")
     */
    public function addArticle(Request $request,  FileUploader $fileUploader)
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
       // $user = $this->getUser();

        $element = new Article();
        $form = $this->createForm(ArticleType::class, $element);
        //$element->setTitle($request->request->get('title'));
        //$element->setIntro($request->request->get('intro'));
        //$element->setText($request->request->get('text'));
        //$element->setLink($request->request->get('link'));
       // $element->setCreatedDate(new \DateTime($request->request->get('createdDate')));
        $element->setPublished(false);
        //$element->setImage($request->request->get('image'));
        //$element->setImage(new File($this->getParameter('images_directory').'/'.$fileName));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $element = $form->getData();

            $file = new UploadedFile($element->getImage(), $element->getImage());
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
                'Nouvel article ajouté !'
            );
            return $this->redirectToRoute('show_article', ['id' => $element->getId()]);
        }
        else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'warning',
                'Oops ! Le formulaire comporte des erreurs '
            );
        }


        return $this->render('theme-a/admin/create-article.html.twig', [
            'site' => $siteInfos,
            'form' => $form->createView(),
            'button_text' => 'Valider',
            'step' => 2,
            'page_title' => 'Nouvel article',
            'categories' => $categories,
        ]);
    }

    /**
     * Route("/article/{id}", name="article_show")
     */
    public function showArticle($id)
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $element = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$element) {
            throw $this->createNotFoundException(
                'No element found for id '.$id
            );
        }

        //return new Response('Check out this great product: '.$element->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('theme-a/pages/article.html.twig', [
            'site' => $siteInfos,
            'element' => $element,
            'published' => $element->getPublished(),
            'page_title' => $element->getTitle(),
            'categories' => $categories
        ]);
    }

    /**
     * Route("/article/unpublish/{id}", name="switch_publish_article")
     */
    public function switchPublishArticle($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $element = $this->getDoctrine()
            ->getRepository(Article::class)
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
        return $this->listArticles();
    }

    /**
     * Route("/article/edit/{id}", name="edit_article")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editArticle($id, Request $request, FileUploader $fileUploader)
    {
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        //$user = $this->getUser();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Article::class)->find($id);
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

        $form = $this->createForm(ArticleType::class, $element);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $element->getImage();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $element->setImage($fileName);

            } else {
                $element->setImage($oldFileName);
            }

            /** @var Article $element */
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Élément modifié !');
            return $this->redirectToRoute('list_articles', [
                'id' => $element->getId(),
                'page_title' => 'Mes articles',
                'categories' => $categories,
            ]);
        }
        return $this->render('theme-a/admin/create-article.html.twig', [
            'site' => $siteInfos,
            'form' => $form->createView(),
            //'miniature' => $oldFileNamePath,
            'id' => $element->getId(),
            'image' => $oldFileName,
            'categories' => $categories,
            'edit' => true,
            //'image' => $element->getImage(),
            'button_text' => 'Modifier !',
            'page_title' => 'Modifier un article'
        ]);
    }


    /**
     * Route("/article/delete/{id}", name="delete_article")
     */
    public function deleteElement($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $element = $entityManager->getRepository(Article::class)->find($id);

        if (!$element) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $entityManager->remove($element);
        $entityManager->flush();

        return $this->redirectToRoute('list_articles');
    }


    /**
     * Route("/articles", name="list_articles")
     */
    public function listArticles()
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        // $user = $this->getUser()
        $siteInfos = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find(1);
        $elements = $this->getDoctrine()
            ->getRepository(Article::class)
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
        return $this->render('theme-a/admin/list.html.twig', [
            'site' => $siteInfos,
            'elements' => $elements,
            'categories' => $categories,
            'page_title' => 'Mes articles',
            'edit_path' => 'edit_article',
            'publish_path' => 'switch_publish_article',
            'delete_path' => 'delete_article',
        ]);
    }
}
