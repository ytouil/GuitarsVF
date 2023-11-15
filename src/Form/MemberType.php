<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full_name')
            ->add('bio')
            ->add('image', VichImageType::class,[
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'mapped' => false,
                'required' => false

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
