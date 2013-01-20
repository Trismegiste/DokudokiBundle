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
        $stratKey = $this->getType($obj);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapToDb($obj);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function recursivCreate($param)
    {
        $stratKey = $this->getType($param);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapFromDb($param);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

    protected function getType($param)
    {
        $default = gettype($param);
        if ('object' == $default) {
            if (array_key_exists(get_class($param), $this->mappingColleague)) {
                $default = get_class($param);
            }
        }

        return $default;
    }

}
