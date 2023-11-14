<?php

namespace App\Form;

use App\Entity\Gallery;
use App\Entity\Guitar;
use App\Entity\Inventory;
use App\Repository\Implementations\GalleryRepository;
use App\Repository\Implementations\InventoryRepository;
use App\Service\Implementations\UserContentService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GuitarType extends AbstractType
{
    private Security $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException('User must be authenticated to create a guitar.');
        }
        $builder
            ->add('modelName')
            ->add('description')
            ->add('image', VichImageType::class)
            ->add('gallery', EntityType::class, [
                'class' => Gallery::class,
                'query_builder' => function (GalleryRepository $gr) use ($user) {
                    return $gr->createQueryBuilder('g')
                        ->where('g.member = :member')
                        ->setParameter('member', $user);
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guitar::class,
        ]);
    }
}
