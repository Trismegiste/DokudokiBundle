<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;

/**
 * PhpCollection maps php collections internal classes
 * Like ArrayObject and SplObjecStorage
 */
class PhpCollection extends AbstractMapper
{

    protected $collectionType = array('ArrayObject', 'SplObjectStorage');

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return is_array($var) &&
                array_key_exists(MapObject::FQCN_KEY, $var) &&
                in_array($var[MapObject::FQCN_KEY], $this->collectionType);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return is_object($var) && in_array(get_class($var), $this->collectionType);
    }

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        $collection = null;

        switch ($var[MapObject::FQCN_KEY]) {
            case 'ArrayObject':
                $collection = new \ArrayObject();
                foreach ($var['content'] as $key => $val) {
                    $collection[$key] = $this->mediator->recursivCreate($val);
                }
                break;

            case 'SplObjectStorage' :
                $collection = new \SplObjectStorage();
                foreach ($var['content']['key'] as $idx => $key) {
                    $val = $this->mediator->recursivCreate($var['content']['value'][$idx]);
                    $objKey = $this->mediator->recursivCreate($key);
                    $collection[$objKey] = $val;
                }
                break;
        }

        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($var)
    {
        $struc[MapObject::FQCN_KEY] = get_class($var);

        switch (get_class($var)) {
            case 'ArrayObject':
                $struc['content'] = $this->dumpArray($var);
                break;

            case 'SplObjectStorage' :
                $struc['content'] = $this->dumpSplStorage($var);
                break;
        }

        return $struc;
    }

    protected function dumpArray(\ArrayObject $arr)
    {
        $content = array();

        foreach ($arr as $key => $val) {
            $content[$key] = $this->mediator->recursivDesegregate($val);
        }

        return $content;
    }

    protected function dumpSplStorage(\SplObjectStorage $arr)
    {
        $contentKey = array();
        $contentVal = array();

        foreach ($arr as $idx => $val) {
            $contentKey[$idx] = $this->mediator->recursivDesegregate($val);
            $contentVal[$idx] = $this->mediator->recursivDesegregate($arr->getInfo());
        }

        return array('key' => $contentKey, 'value' => $contentVal);
    }

}