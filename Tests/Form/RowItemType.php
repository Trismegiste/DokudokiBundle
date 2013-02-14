<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * RowItemType is a test case for MagicFormType
 * A Product with a quantity
 * Bound to an array
 *
 * @author florent
 */
class RowItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('qt');
        $builder->add('item', new ProductType());
    }

    public function getName()
    {
        return 'item';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => null));
    }

}