<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CartPlusType is a test case for MagicFormType
 *
 * @author florent
 */
class CartPlusType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address');
        $builder->add('row', 'collection', array('type' => new RowItemType(), 'allow_add' => true));
    }

    public function getName()
    {
        return 'cart';
    }

    public function getParent()
    {
        return 'magic_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('class_key' => $this->getName()));
    }

}