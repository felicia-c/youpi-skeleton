<?php

namespace App\Form;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            //informations générales
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'label' => 'Titre du site',
                'required'   => true,
            ])

            ->add('owner', TextType::class, [
                'label' => 'Propriétaire du site',
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('siteContact', TextType::class, [
                'label' => 'email de contact (destinataire des messages)',
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])

            ->add('theme', TextType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('logo', FileType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])

            // Home page
                // Hero header
            ->add('headerBgImage', FileType::class, [
                'label' => 'Image background',
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('preTitle', TextType::class, [
                'label' => 'Texte haut (avant le titre principal)',
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('mainTitle', TextType::class, [
                'label' => 'Titre principal',
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('subTitle', TextType::class, [
                'label' => 'Sous-titre' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
                //Block introduction
            ->add('introTitle', TextType::class, [
                'label' => 'Titre de l\'intro',
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('introText', TextareaType::class, [
                'label' => 'Texte de l\'intro',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'editor'
                ),
                'required'   => false,
            ])
            ->add('commercialBandTitle', TextType::class, [
                'label' => 'Titre' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('commercialBandText', TextareaType::class, [
                'label' => 'Texte' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('commercialBandButtonText', TextType::class, [
                'label' => 'Bouton vers \'contact\' (texte)' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('quoteText', TextareaType::class, [
                'label' => 'Texte' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('quoteAuthor', TextType::class, [
                'label' => 'Auteur (ligne 1)' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('quoteAuthorStatus', TextType::class, [
                'label' => 'Auteur (ligne 2)' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
            ->add('contactBlockText', TextareaType::class, [
                'label' => 'Texte du block contact' ,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
