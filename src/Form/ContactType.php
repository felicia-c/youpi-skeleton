<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'form-control',
                ),
                'required'   => true,
            ])
            ->add('from', EmailType::class, [
                'label'=> 'E-mail',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'form-control',
                ),
                'required'   => true,
            ])
            ->add('message', TextareaType::class, [
                'label'=> 'Message',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'form-control'
                ),
                'required'   => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
