<?php

namespace App\Form;

use App\Entity\CustomerEntity;
use App\Entity\ReservationEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationFormType extends AbstractType
{
    
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateFrom', null, [
                'widget' => 'single_text',
            ])
            ->add('dateTo', null, [
                'widget' => 'single_text',
            ])
            ->add('adults')
            ->add('customer', EntityType::class, [
                'required' => true,
                'class' => CustomerEntity::class,
                'choice_label' => 'lastName',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Booked' => 0,
                    'In progress' => 1,
                    'Done' => 2,
                    'Cancelled' => 3,
                    'Never arrived' => 4,
                ]
            ])
            ->add('notice', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ]
            ])
            ->add('children')
            ->add('breakfast')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'button button-primary',
                ]
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => [
                    'class' => 'button button-warnings',
                    'onClick' => 'window.location.href="' . $this->urlGenerator->generate('rooms_list', ['page' => $options['currentPage']]) . '"'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationEntity::class,
            'currentPage' => null,
        ]);
    }
}
