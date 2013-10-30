<?php

/*
 * Dokudokibundle
 */

namespace tests\Fixtures;

use Trismegiste\DokudokiBundle\Persistence\Persistable;

class Order extends Cart implements Persistable
{

    use \Trismegiste\DokudokiBundle\Persistence\PersistableImpl;
}