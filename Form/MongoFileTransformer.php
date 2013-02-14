<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transformer MongoBinData <-> UploadedFile
 *
 * @author flo
 */
class MongoFileTransformer implements DataTransformerInterface
{

    public function transform($bindata)
    {
        if (null === $bindata) {
            return null;
        }

        return null;
    }

    public function reverseTransform($uploadfile)
    {
        if (is_null($uploadfile)) {
            return null;
        }

        $model = new \MongoBinData(file_get_contents($uploadfile->getRealPath()), 2);
        return $model;
    }

}
