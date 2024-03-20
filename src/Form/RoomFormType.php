<?php

namespace App\Form;

use App\Entity\RoomEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RoomFormType extends AbstractType
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bathroom')
            ->add('size', IntegerType::class, [
                    'label' => 'Size in sq meters',
            ])
            ->add('persons')
            ->add('balcony')
            ->add('fridge')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'button button-primary',
                ]
            ])
            ->add('price_weekday')
            ->add('price_weekend')
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
            'data_class' => RoomEntity::class,
            'currentPage' => null,
        ]);
    }
}
