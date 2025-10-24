<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as AssertAssert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d’utilisateur',
                'attr' => ['placeholder' => 'Nom d’utilisateur'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom ne doit pas être vide.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[\p{L}\p{N}\s_\-.,!?()\"]+$/u',
                        'message' => 'Le nom est invalide.'
                    ]),
                    new Assert\Length(
                        min: 3,
                        max: 50,
                        minMessage: "Le nom doit contenir au moins 3 caractères.",
                        maxMessage: "Le nom ne peut pas dépasser 50 caractères."
                    )
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Email'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'email ne doit pas être vide.'
                    ]),
                    new Assert\Email([
                        'message' => 'L\'email est invalide.'
                    ])
                ]
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (image)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image valide (jpeg, png, gif)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'crsf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'tweet_item',
        ]);
    }
}
