<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('instruction')
            ->add('options', CollectionType::class, [
                'entry_type' => OptionType::class,
                'entry_options' => [
                    'label' => false,
                    'in_collection' => true
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Options',
            ])
            // ->add('mission', EntityType::class, [
            //     'class' => Mission::class,
            //     'choice_label' => 'label',
            // ])
            // ->add('answeredQuestion', EntityType::class, [
            //     'class' => AnsweredQuestion::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
