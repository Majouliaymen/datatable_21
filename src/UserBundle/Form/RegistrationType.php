<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 14/02/2018
 * Time: 19:39
 */

namespace UserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
        $builder->add('prenom');

        $builder->add('roles', ChoiceType::class, array(
            'label' => 'Role',
            'choices' => array(

                'baby_sitter' => 'ROLE_BABY_SITTER',
                'responsable boutique' => 'ROLE_VENDEUR',
                'responsable jardin' => 'ROLE_PROPRIETAIRE',
                'parent' => 'ROLE_PARENT',
                'admin' => 'ROLE_ADMIN',
            ),
            'required' => true,
            'multiple' => true));
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    public function getBlockPrefix()
    {
        return 'firas_user_registration';
    }
}