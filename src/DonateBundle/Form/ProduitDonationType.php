<?php

namespace DonateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitDonationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('description')

            ->add('categorie', ChoiceType::class, array(
                'choices' => array(

                    'outils school' => 'outils school',
                    'clothes' => 'clothes',
                    'Autre' => 'Autre'
                ),
            ))
            ->add('image', FileType::class, array('data_class' => null))
            ->add('tel')->add('adresse')->add('genre',ChoiceType::class,
                array('choices' => array(
                    'Fille' => 'Fille',
                    'Garçon' => 'Garçon',
                ),
                    'choices_as_values' => true,'multiple'=>false,'expanded'=>true))->add('Valider',SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DonateBundle\Entity\ProduitDonation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'donatebundle_produitdonation';
    }


}
