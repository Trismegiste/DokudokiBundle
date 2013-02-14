<?php

/*
 * mongosapin
 */

namespace Trismegiste\DokudokiBundle\Form;

use Symfony\Component\Form\DataMapperInterface;

/**
 * MagicMapper maps a Magic\Document instance to a form and vice versa
 *
 * @author flo
 */
class MagicMapper implements DataMapperInterface
{

    public function mapDataToForms($data, array $forms)
    {
        if (!is_null($data)) {
            $iter = $data->getIterator();
            $struc = iterator_to_array($iter, true);
            foreach ($forms as $form) {
                $key = $form->getName();
                if (array_key_exists($key, $struc)) {
                    $form->setData(call_user_func(array($data, 'get' . ucfirst($key))));
                }
            }
        }
    }

    public function mapFormsToData(array $forms, &$data)
    {
        foreach ($forms as $key => $field) {
            call_user_func(array($data, 'set' . ucfirst($key)), $field->getData());
        }
    }

}
