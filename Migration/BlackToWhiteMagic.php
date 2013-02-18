<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapArray;
use Trismegiste\DokudokiBundle\Migration\Analyser;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;

/**
 * BlackToWhiteMagic is a service for migrating from BlackMagic or Hoodoo stages
 * to WhiteMagic stage
 * 
 */
class BlackToWhiteMagic extends StageMigration
{

    protected $classStat;

    protected function buildMapper(Mediator $algo)
    {
        $catcher = new Analyser\AliasCollector($algo);
        $this->classStat = &$catcher->collector;
        new MapArray($algo);
        new Analyser\BlackHole($algo);
    }

    public function analyse()
    {
        $cardinal = parent::analyse();
        $this->classStat['root'] = $cardinal;

        return $this->classStat;
    }

    public function generate(array $alias)
    {
        $result = array();
        foreach ($alias as $classAlias => $classParam) {
            print_r($classParam);
            if (array_key_exists('property', $classParam)) {
                $template = (array_key_exists('_id', $classParam)) ? 'GetterSetterPersistable' : 'GetterSetterClass';
            } else {
                $property = array();
                $template = 'GetterSetterClass';
            }
            $fqcn = $classParam['fqcn'];
            preg_match('#(.+)\\\\([^\\\\]+)$#', $fqcn, $extract);
            $classNamespace = $extract[1];
            $className = $extract[2];
       //     $result[] = include $template;
        }
        
        return $result;
    }

}