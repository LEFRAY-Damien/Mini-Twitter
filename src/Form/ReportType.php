<?php

namespace App\Form;

use App\Entity\Report;
use App\Entity\Tweet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Expliquez nous la raison de ce signalement.',
                    'maxlength' => 280,
                    'style' => 'resize: none;',
                    'rows' => 4,
                    'cols' => 60,
                    'class' => 'text-white border border-white rounded-lg p-3 bg-[#15202B]'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\p{L}\p{N}\s_\-.,!?()\'"]+$/u',
                        'message' => 'Le signalement ne peut contenir seulement des lettres, chiffres ou underscores.'
                    ]),
                    new Assert\NotBlank([
                        'message' => 'Le signalement ne peut pas être vide.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'crsf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'tweet_item',
        ]);
    }
}
