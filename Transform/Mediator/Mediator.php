<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Mediator
 *
 * Mediator is the delegation of Factory to recursively traverse
 * objects and arrays. Responsible for maintaining a list of Colleague (the
 * mappers converting object, array, scalar, MongoDate, etc. )
 *
 * @author flo
 */
class Mediator extends AbstractMediator
{

    const FQCN_KEY = '-class';

    /**
     * {@inheritDoc}
     */
    public function recursivDesegregate($obj)
    {
        $stratKey = $this->getType(self::DESEGREGATE, $obj);
        return $this->mappingColleague[self::DESEGREGATE][$stratKey]->mapToDb($obj);
    }

    /**
     * {@inheritDoc}
     */
    public function recursivCreate($param)
    {
        $stratKey = $this->getType(self::CREATE, $param);
        return $this->mappingColleague[self::CREATE][$stratKey]->mapFromDb($param);
    }

    /**
     * Ugly method to replace by a Chain of Responsibility pattern
     * see MapObject::mapFromDb to remove other check
     *
     * @param type $way
     * @param type $param
     * @return type
     * @throws \DomainException
     */
    protected function getType($way, $param)
    {
        $pool = $this->mappingColleague[$way];
        $default = gettype($param);

        if (!array_key_exists($default, $pool)) {
            throw new \DomainException("Unsupported type $default");
        }

        if ('array' == $default) {
            if (array_key_exists(self::FQCN_KEY, $param)) {
                $default = 'object';
                if (array_key_exists($param[self::FQCN_KEY], $pool)) {
                    $default = $param[self::FQCN_KEY];
                }
            }
        } else {
            if ('object' == $default) {
                if (array_key_exists(get_class($param), $pool)) {
                    $default = get_class($param);
                }
            }
        }

        return $default;
    }

}
