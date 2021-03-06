<?php

namespace App\Form;

use DateTime;
use App\Entity\Tag;
use App\Entity\Task;
use PhpParser\Node\Stmt\Label;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Twig\Node\Expression\Binary\SubBinary;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => "Nom de la tâche"])
            ->add('description', TextareaType::class, [
                'label' => 'Descritption'
            ])
            ->add('dueAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date Effecive'
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn-danger'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
