<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

use Trismegiste\DokudokiBundle\Persistence\Persistable;

class Order extends Cart implements Persistable
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