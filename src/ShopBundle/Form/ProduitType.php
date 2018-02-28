<?php

namespace ShopBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
            'attr' => array(
                'placeholder' => 'nom du produit',
                'class'=>'form-control border-color-1'
            )))
            ->add('stock',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Stock',
                    'class'=>'form-control border-color-2'
                )) )
            ->add('prix',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Prix',
                    'class'=>'form-control border-color-4'
                )))
            ->add('genre', ChoiceType::class, array(
                //'class'=>'form-control border-color-4',
                'choices' => array(

                    'Garçon' => 'Garçon',
                    'Fille' => 'Fille',
                ),
                'attr' => array(
                    'placeholder' => 'Sexe',
                    'class'=>'form-control border-color-3'
                )))

            ->add('age',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Age',
                    'class'=>'form-control border-color-5'
                )))
            ->add('description',TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class'=>'form-control border-color-1'
                )) )

            ->add('image', FileType::class,array('data_class' => null))
            ->add('Ajouter',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-primary'
                )));    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'shopbundle_produit';
    }


}
