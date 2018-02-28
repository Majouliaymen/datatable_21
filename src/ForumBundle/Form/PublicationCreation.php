<?php

namespace ForumBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PublicationCreation extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('titre')->add('pieceJointe')->add('contenu', CKEditorType::class, array(
            'config' => array(
                'uiColor' => '#ffffff',
                //...
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'forum_bundle_question_creation';
    }
}
