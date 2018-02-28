<?php

namespace JardinBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JardinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',TextType::class, array(
            'attr' => array(
                'placeholder' => 'nom du Jardin',
                'class'=>'form-control border-color-1'
            )))
            ->add('adressemail',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'EMail',
                    'class'=>'form-control border-color-2'
                )) )
            ->add('numtel',NumberType::class, array(
                'attr' => array(
                    'placeholder' => 'EMail',
                    'class'=>'form-control border-color-2'
                )) )
            ->add('adresse',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'adresse',
                    'class'=>'form-control border-color-4'
                )))
            ->add('description',TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'rows'=>'7',
                    'class'=>'form-control border-color-1'
                )) )

            ->add('logo', FileType::class,array('data_class' => null))
            ->add('update',SubmitType::class, array(
                'attr' => array(
                    'value'=>'MEttre A jour',
                    'class'=>'btn btn-flat btn-colored btn-theme-color-lemon mt-1'
                )));    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JardinBundle\Entity\Jarenfant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jardinbundle_jardin';
    }


}
