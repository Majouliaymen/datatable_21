<?php

namespace EventsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('description')
            ->add('type',ChoiceType::class,
                array('choices' => array(
                    'Gratuit' => 'Gratuit',
                    'Payant' => 'Payant',
                ),
                    'choices_as_values' => true,'multiple'=>false,'expanded'=>true))
            ->add('prix')


            ->add('adresse')
            ->add('image',FileType::class, array('data_class' => null))
            ->add('valider','Symfony\Component\Form\Extension\Core\Type\SubmitType');




    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventsBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eventsbundle_evenement';
    }


}
