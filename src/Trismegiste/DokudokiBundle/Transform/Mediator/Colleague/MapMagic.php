<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * MapMagic is a mapper to and from a Magic Document
 * Must be responsible before MapObject to shortcut the mapping
 *
 * @author florent
 */
class MapMagic extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        $dynClass = $param[Document::classKey];
        $obj = new Document($dynClass);
        unset($param[Document::classKey]);

        foreach ($param as $key => $val) {
            // go deeper
            $mapped = $this->mediator->recursivCreate($val);
            // set the value with magic
            call_user_func(array($obj, 'set' . ucfirst($key)), $mapped);
        }

        return $obj;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($obj)
    {
        $dump = array();
        foreach ($obj->getIterator() as $key => $val) {
            $dump[$key] = $this->mediator->recursivDesegregate($val);
        }

        return $dump;
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'array') && array_key_exists(Document::classKey, $var);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return (gettype($var) == 'object') && ($var instanceof Document);
    }

}