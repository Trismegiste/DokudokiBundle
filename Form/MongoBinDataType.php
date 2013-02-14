<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * MongoBinDataType is a field form type for storing file in MongoDB
 *
 * @author flo
 */
class MongoBinDataType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new MongoFileTransformer();
        $builder->addModelTransformer($transformer);
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'dokudoki_file';
    }

}
