<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormInterface;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * This is a form for Magic\Document, it is mostly presetting datamapper
 * and some factory trick for new instance.
 *
 * Usage :
 * 1. subclass an AbstractType to make your form (do not subclass this class : symfony recommandations )
 * 2. Override setDefaultOptions and set the property 'class_key',
 *    the same value will be persisted in the key classKey of the Document
 * 3. Override getParent to return 'magic_form'
 *
 * @todo Add a check between getClassName() and class_key
 */
class MagicFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new MagicMapper());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('class_key'));

        $emptyData = function (Options $options) {
                    $classKey = $options['class_key'];

                    return function (FormInterface $form) use ($classKey) {
                                return $form->isEmpty() && !$form->isRequired() ? null : new Document($classKey);
                            };
                };

        $resolver->setDefaults(array(
            'empty_data' => $emptyData,
            'data_class' => 'Trismegiste\DokudokiBundle\Magic\Document'
        ));
    }

    public function getName()
    {
        return 'magic_form';
    }

    public function getParent()
    {
        return 'form';
    }

}
