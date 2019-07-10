<?php
namespace App\Form;

use App\Entity\Creation;
use App\Entity\Element;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        $group = new Category();
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
            ->add('category',  EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'multiple' => true,
                'expanded' => true,
                'choices' => $group->getName(),
                /*
                'choices' => [
                    new Category('sols'),
                    new Category('jardin'),
                    new Category('terrasse'),
                    new Category('Cat4'),
                ],*/
                'choice_label' => function(Category $category, $key, $value) {
                    return $category->getName();
                },
                'choice_attr' => function(Category $category, $key, $value) {
                    return ['class' => 'category_'.strtolower($category->getName())];
                },
                /*
                'group_by' => function(Category $category, $key, $value) {
                    // randomly assign things into 2 groups
                    return rand(0, 1) == 1 ? 'Group A' : 'Group B';
                },

                'preferred_choices' => function(Category $category, $key, $value) {
                    return $category->getName() == 'Cat2' || $category->getName() == 'Cat3';
                },
                */
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
            ->add('altImage', TextareaType::class, [

                'label' => 'Que représente cette image ?',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'form-control',
                ),
                'required'   => true,
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