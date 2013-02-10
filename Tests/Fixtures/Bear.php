<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

use Trismegiste\DokudokiBundle\Transform\Cleanable;

class Bear implements Cleanable
{

    protected $answer = 42;
    protected $transient;

    public function __construct()
    {
        $this->transient = range(1, 10);
    }

    public function getTransient()
    {
        return $this->transient;
    }

    public function wakeup()
    {
        $this->__construct();
    }

    public function sleep()
    {
        $this->transient = null;
    }

}