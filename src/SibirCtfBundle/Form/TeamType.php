<?php
namespace SibirCtfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use SibirCtfBundle\Entity\Member;
use SibirCtfBundle\Entity\Team;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Название команды',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Введите название команды'
                )
            ))
            ->add('logo', FileType::class, array(
                'label' => 'Ссылка на логотип',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => ''
                )
            ))
            ->add('members', CollectionType::class, array(
                'label' => 'Участники команды',
                'attr' => array('class' => 'form-control'),
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => 'SibirCtfBundle\Form\MemberType',
            ))
            ->add('city', TextType::class, array(
                'label' => 'Город',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Введите город'
                )
            ))
            ->add('school', TextType::class, array(
                'label' => 'Учебное заведение',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Сокращенное название'
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'E-mail',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Сюда мы сообщим о заявке'
                )
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Телефон',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Введите телефон капитана'
                )
            ))
            ->add('conference', CheckboxType::class, array(
                'label' => 'Выступление на конференции',
                'required' => false
            ))
           ;
    }
}