<?php
namespace SibirCtfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SibirCtfBundle\Entity\Member;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('captain', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', array(
            'label' => 'Кто капитан',
            'attr' => array('class' => 'form-control'),
            'required' => false
        ));

        $builder->add('fio', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
            'label' => 'Фамилия имя отчество',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Введите ФИО участника'
            )
        ));

        $builder->add('mail', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
            'label' => 'Электронная почта',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Введите e-mail участника'
            )
        ));


        $builder->add('size', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
            'label' => 'Размер футболки',
            'attr' => array('class' => 'form-control'),
            'choices'  => array(
                'XS' => 'XS',
                'S' => 'S',
                'M' => 'M',
                'L' => 'L',
                'XL' => 'XL',
                'XXL' => 'XXL',
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SibirCtfBundle\Entity\Member',
        ));
    }
}