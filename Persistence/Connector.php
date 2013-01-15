<?php

/*
 * Persistence
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * Description of Connector
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

    public function getCollection()
    {
        $classCnx = (version_compare(phpversion('mongo'), '1.3') > 0) ? '\MongoClient' : '\Mongo';

        $cnx = new $classCnx('mongodb://' . $this->paramValid['server']);

        return $cnx->selectCollection($this->paramValid['database'], $this->paramValid['collection']);
    }

}
