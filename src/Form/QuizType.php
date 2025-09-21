<?php

namespace App\Form;

use App\Entity\Option;
use App\Repository\OptionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

// class QuizType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('question', EntityType::class, [
//                 'label' => $options['question']->getInstruction(),
//                 'class' => Option::class,
//                 'choices' => $options['question']->getOptions(),
//                 'choice_label' => 'label',
//                 'expanded' => true,
//                 'multiple' => false,
//                 'query_builder' => function (OptionRepository $er) use ($options) {
//                     return $er->createQueryBuilder('o')
//                         ->where('o.question = :question')
//                         ->setParameter('question', $options['question']);
//                 }
//             ]);
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'question' => null,
//         ]);
//     }
// }
// src/Form/QuizMissionType.php
namespace App\Form;

use App\Entity\Option;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Question[] $questions */
        $questions = $options['questions'] ?? [];

        foreach ($questions as $question) {
            $builder->add('question_' . $question->getId(), EntityType::class, [
                'label' => $question->getInstruction(),
                'class' => Option::class,
                'choices' => $question->getOptions(),
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Sélectionnez une réponse',
            ]);
        }
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'questions' => [],
        ]);
    }
}
