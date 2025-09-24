<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, [
                'label' => 'Answer Text',
                'attr' => ['placeholder' => 'Enter the answer text']
            ])
            ->add('code', null, [
                'label' => 'Code (optional)',
                'required' => false,
                'attr' => ['placeholder' => 'Enter a code for this option (optional)']
            ])
            ->add('isCorrect', null, [
                'label' => 'Is this the correct answer?',
                'required' => false
            ])
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'instruction',
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Option::class,
        ]);
    }
}
