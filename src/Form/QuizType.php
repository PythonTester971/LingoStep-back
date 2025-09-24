<?php

namespace App\Form;

use App\Entity\Option;
use App\Repository\OptionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', EntityType::class, [
                'label' => $options['question']->getInstruction(),
                'class' => Option::class,
                'choices' => $options['question']->getOptions(),
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => false,
                'query_builder' => function (OptionRepository $er) use ($options) {
                    return $er->createQueryBuilder('o')
                        ->where('o.question = :question')
                        ->setParameter('question', $options['question']);
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'question' => null,
        ]);
    }
}
