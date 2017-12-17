<?php
namespace SibirCtfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SibirCtfBundle\Entity\News;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
            'label' => 'Заголовок новости',
            'attr' => array('class' => 'form-control'),
            'required' => true
        ));

        $builder->add('poster', 'Symfony\Component\Form\Extension\Core\Type\FileType', array(
            'label' => 'Постер новости',
            'attr' => array('class' => 'form-control'),
            'required' => true
        ));

        $builder->add('short_text', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
            'label' => 'Короткий текст новости',
            'attr' => array(
                'class' => 'form-control',
                'rows' => '4',
                'placeholder' => 'Введите короткий текст новости'
            )
        ));

        $builder->add('full_text', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
            'label' => 'Полный текст новости',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Введите текст новости'
            )
        ));

        $builder->add('date', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
            'label' => 'Дата создания новости'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SibirCtfBundle\Entity\News',
        ));
    }
}