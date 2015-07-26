<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration\Analyser;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapMagic;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * AliasCollector collects aliases from magic document
 */
class AliasCollector extends MapMagic
{

    public $collector = array('found' => array(), 'properties' => array());

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        $alias = $param[Document::classKey];
        $this->incStatForKey('found', $alias);
        unset($param[Document::classKey]);

        foreach ($param as $key => $val) {
            $this->collector['properties'][$alias][$key] = true;
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