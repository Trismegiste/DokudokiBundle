<?php

/*
 * Persistence
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * A concrete connector against MongoDB. It's a wrapper to encapsulate config
 *
 * @author flo
 */
class Connector
{

    protected $paramValid;

    public function __construct(array $param)
    {
        $this->paramValid = $param;
    }

    /**
     * Returns the mongo collection with the parameters setted in constructor
     * 
     * @return \MongoCollection
     */
    public function getCollection()
    {
        $classCnx = (version_compare(phpversion('mongo'), '1.3') > 0) ? '\MongoClient' : '\Mongo';

        $cnx = new $classCnx('mongodb://' . $this->paramValid['server']);

        return $cnx->selectCollection($this->paramValid['database'], $this->paramValid['collection']);
    }

}
