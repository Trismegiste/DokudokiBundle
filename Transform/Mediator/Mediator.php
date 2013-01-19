<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

use Trismegiste\DokudokiBundle\Utils\ReflectionClassBC;

/**
 * Description of Mediator
 *
 * @author flo
 */
class Mediator
{

    protected $mappingColleague = array();

    public function registerType($name, Mapping $colleague)
    {
        if (is_string($name)) {
            $name = array($name);
        }
        foreach ($name as $key) {
            $this->mappingColleague[$key] = $colleague;
        }
    }

    public function recursivDesegregate($obj)
    {
        $stratKey = gettype($obj);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapToDb($obj);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

    /**
     * Recursion for restoration
     *
     * @param array $param
     * @return array
     */
    public function recursivCreate(array $param)
    {
        $modeObj = isset($param[MapObject::FQCN_KEY]);

        if ($modeObj) {
            $fqcn = $param[MapObject::FQCN_KEY];
            unset($param[MapObject::FQCN_KEY]);
            $reflector = new ReflectionClassBC($fqcn);
            $vectorOrObject = $reflector->newInstanceWithoutConstructor();
        } else {
            $vectorOrObject = array();
        }

        foreach ($param as $key => $val) {
            if (is_array($val)) {
                // go deeper
                $mapped = $this->recursivCreate($val);
            } else {
                $mapped = $val;
            }

            // set the value
            if ($modeObj) {
                if ($reflector->hasProperty($key)) {
                    $prop = $reflector->getProperty($key);
                    $prop->setAccessible(true);
                    $prop->setValue($vectorOrObject, $mapped);
                } else {
                    // injecting schemaless property
                    $vectorOrObject->$key = $val;
                }
            } else {
                $vectorOrObject[$key] = $mapped;
            }
        }

        return $vectorOrObject;
    }

}
