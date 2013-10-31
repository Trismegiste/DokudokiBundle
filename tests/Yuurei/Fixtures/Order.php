<?php

/*
 * Dokudokibundle
 */

namespace tests\Yuurei\Fixtures;

use Trismegiste\Yuurei\Persistence\Persistable;

class Order extends Cart implements Persistable
{

    use \Trismegiste\Yuurei\Persistence\PersistableImpl;
}