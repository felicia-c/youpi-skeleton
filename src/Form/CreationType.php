<?php
namespace App\Form;

use App\Entity\Creation;
use App\Entity\Element;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class CreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // $defaultDate = new DateTime('now');
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required'   => true,
                ])
            ->add('achievement_date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => 'form-control',
                ),
                'invalid_message' => 'format invalide',
                ])
            ->add('description', TextareaType::class, [

                'attr' => array(
                    'placeholder' => '',
                    'class' => 'form-control',
                ),
                'required'   => true,
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,

                //'empty_data' => 'build/assets/images/leafs.jpg',
                ])
            //->add('save', SubmitType::class, ['label' => 'Étape suivante'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Creation::class,
        ]);
    }

}