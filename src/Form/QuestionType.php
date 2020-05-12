<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',null, [
                "label" => false,
                "attr" =>[
                    'rows' =>5,
                    'class' => "form-control", 
                    'resizable' => false,
                    'placeholder' => "Saisis ta question, ta demande, ta préoccupation ici, et hop: je réponds !",
                ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
