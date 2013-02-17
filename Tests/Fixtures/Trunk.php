<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

use Trismegiste\DokudokiBundle\Persistence\Persistable;

/**
 * Trunk is a fixture class for migration tests
 */
class Trunk extends Branch implements Persistable
{

    protected $id;

    public function setId(\MongoId $pk)
    {
        $this->id = $pk;
    }

    public function getId()
    {
        return $this->id;
    }

}