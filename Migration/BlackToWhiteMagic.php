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
    protected $aliasConfig;

    public function __construct(\MongoCollection $coll, array $config)
    {
        parent::__construct($coll);
        $this->aliasConfig = $config;
    }

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

    public function filter()
    {
        $cardinal = parent::analyse();
        $report = array();
        foreach ($this->classStat['found'] as $alias => $counter) {
            $classReport = array();
            $classReport['fqcn'] = isset($this->aliasConfig[$alias]) ? $this->aliasConfig[$alias] : 'Not\Found\FQCN';
            if (array_key_exists($alias, $this->classStat['properties'])) {
                foreach ($this->classStat['properties'][$alias] as $prop => $dummy) {
                    $classReport['properties'][] = $prop;
                }
            }
            $report[$alias] = $classReport;
        }
        return array('alias' => $report);
    }

    public function generate(array $alias)
    {
        $result = array();
        foreach ($alias as $classAlias => $classParam) {

            $property = (array_key_exists('properties', $classParam)) ? $classParam['properties'] : array();

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