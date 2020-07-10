<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Categorys;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCreationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorys' , EntityType::class, [
                'class' => Categorys::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function(EntityRepository $er)
                {
                    return $er->createQueryBuilder("c")
                        ->orderBy('c.title' ,'asc');
                },
            ])
            ->add('currency', ChoiceType::class, [
                'multiple' => false,
                'choices' => [
                    'EUR' => "EUR",
                    'USD' => "USD",
                ],

            ])
            ->add('amount')
            ->add('title')
            ->add('content')
            ->add('author', EntityType::class,[
                'class' => Author::class,
                'choice_label' => 'fullname',
                'query_builder' => function(EntityRepository $er)
                {
                    return $er->createQueryBuilder("a")
                        ->orderBy('a.LastName , a.firstName', 'asc');
                },

            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
