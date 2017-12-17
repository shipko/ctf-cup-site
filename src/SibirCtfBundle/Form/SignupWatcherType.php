<?php
namespace SibirCtfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupWatcherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fio', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
            'label' => 'Фамилия имя отчество',
            'attr' => array(
                'class' => 'form-control'
            ),
            'required' => true
        ));

        $builder->add('mail', 'Symfony\Component\Form\Extension\Core\Type\EmailType', array(
            'label' => 'Email',
            'attr' => array(
                'class' => 'form-control'
            ),
            'required' => true
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SibirCtfBundle\Entity\Watcher',
        ));
    }
}