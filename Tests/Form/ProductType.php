<?php

/*
 * sf2ffbp
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ProductType is a test case for MagicFormType
 *
 * @author florent
 */
class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('price');
    }

    public function getName()
    {
        return 'product';
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