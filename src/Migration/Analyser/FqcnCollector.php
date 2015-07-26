<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration\Analyser;

use Trismegiste\Alkahest\Transform\Mediator\Colleague\MapObject;

/**
 * FqcnCollector collects FQCN from objects stored with Invocation stage
 */
class FqcnCollector extends MapObject
{

    public $collector = array('missing' => array(), 'fqcn' => array());

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        $fqcn = $param[self::FQCN_KEY];
        $stat = (!class_exists($fqcn)) ? 'missing' : 'fqcn';
        $this->incStatForKey($stat, $fqcn);
        unset($param[self::FQCN_KEY]);

        foreach ($param as $key => $val) {
            // go deeper
            $this->mediator->recursivCreate($val);
        }

        return null;
    }

    protected function incStatForKey($stat, $key)
    {
        if (array_key_exists($key, $this->collector[$stat])) {
            $this->collector[$stat][$key]++;
        } else {
            $this->collector[$stat][$key] = 1;
        }
    }

}