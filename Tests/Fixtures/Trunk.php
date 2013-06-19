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

    use \Trismegiste\DokudokiBundle\Persistence\PersistableImpl;
}