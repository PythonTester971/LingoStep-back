<?php

namespace App\Form;

use App\Entity\AnsweredQuestion;
use App\Entity\Mission;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('instruction')
            ->add('mission', EntityType::class, [
                'class' => Mission::class,
                'choice_label' => 'id',
            ])
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
