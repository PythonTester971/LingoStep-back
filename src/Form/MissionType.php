<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\LanguageCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('description')
            ->add('xpReward')
            ->add('illustration')
            ->add('languageCourse', EntityType::class, [
                'class' => LanguageCourse::class,
                'choice_label' => 'label',
                // ->add('createdAt', null, [
                //     'widget' => 'single_text',
                // ])
                // ->add('updatedAt', null, [
                //     'widget' => 'single_text',
                // ])
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
