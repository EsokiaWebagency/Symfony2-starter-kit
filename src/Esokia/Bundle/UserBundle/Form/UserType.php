<?php

namespace Esokia\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    
    protected $roles;
    protected $create;

    public function __construct($roles, $create=false) {
        $this->create = $create;
        $this->roles = array();
        foreach($roles['ROLE_ALL'] as $role){
            $this->roles[$role] = strtolower(str_replace('ROLE_', '', $role));
        }
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('label'=>'Username'));
        if($this->create){
             $builder->add('password', 'password', array('label'=>'password'));
        }
         $builder
                 ->add('firstname', 'text', array('label'=>'Firstname','required'=>false, 'attr'=>array('class'=>'notRequired')))
            ->add('name', 'text', array('label'=>'Name','required'=>false))
            ->add('email', 'email', array('label'=>'Email'))
            ->add('locked', 'checkbox', array('label'=>'Locked?','required'=>false))
            ->add('roles', 'choice', array('label'=>'Roles', 'choices' => $this->roles, 'expanded'=>true, 'multiple'=>true ))
        ;
        
        
    }
    
   

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Esokia\Bundle\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'esokia_userbundle_user';
    }
}
