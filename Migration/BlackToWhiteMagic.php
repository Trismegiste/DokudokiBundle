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

            $property = (array_key_exists('property', $classParam)) ? $classParam['property'] : array();

            if (false !== $idx = array_search('_id', $property)) {
                $template = 'GetterSetterPersistable';
                unset($property[$idx]);
            } else {
                $template = 'GetterSetterClass';
            }

            preg_match('#(.+)\\\\([^\\\\]+)$#', $classParam['fqcn'], $extract);
            $classNamespace = $extract[1];
            $className = $extract[2];
            ob_start();
            include __DIR__ . '/../Resources/template/' . $template . '.php';
            $result[] = ob_get_clean();
        }

        return $result;
    }

}