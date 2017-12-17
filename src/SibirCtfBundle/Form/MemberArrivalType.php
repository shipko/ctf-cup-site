<?php
namespace SibirCtfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SibirCtfBundle\Entity\Member;

class MemberArrivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('arrival_description', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
            'label' => 'Напишите, когда вы приедете, сколько вас будет и где встречать (если команда прибывает по отдельности, то укажите сколько будет человек)',
            'attr' => array(
                'class' => 'form-control',
                'rows' => 4
            ),
            'required' => true
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SibirCtfBundle\Entity\User',
        ));
    }
}