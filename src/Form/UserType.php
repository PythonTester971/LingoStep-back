<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Language;
use App\Entity\LanguageCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('username')
            ->add('picturePath')
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'label',
            ])
            ->add('languageCourses', EntityType::class, [
                'class' => LanguageCourse::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'mapped' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
