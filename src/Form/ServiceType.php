<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title1',TextType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'label' => 'Titre (1ère ligne)',
                'required'   => false,
            ])
            ->add('title2',TextType::class, [
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                    'label' => 'Titre (2de ligne)',
                    'required'   => false,
                ])
            ->add('description',TextareaType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'label' => 'Descriprion',
                'required'   => false,
            ])
            ->add('image_home',FileType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'label' => 'Image',
                'required'   => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
