<?php

namespace App\Form;

use App\Entity\CustomerEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerFormType extends AbstractType
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('phone', TelType::class)
            ->add('email', EmailType::class)
            ->add('street_name')
            ->add('zip')
            ->add('city')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'button button-primary',
                ]
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => [
                    'class' => 'button button-warnings',
                    'onClick' => 'window.location.href="' . $this->urlGenerator->generate('customers_list', ['page' => 1]) . '"'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerEntity::class,
            'currentPage' => null,
        ]);
    }
}
