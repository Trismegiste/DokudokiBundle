<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

class VerifMethod
{

    protected $vat = 19.6;
    protected $price;

    public function __construct($x)
    {
        $this->price = $x;
    }

    public function getTotal()
    {
        return $this->price * (1 + $this->vat / 100);
    }

}

class IntoVoid implements Skippable
{
    
}

class Bear implements Cleanable
{

    protected $answer = 42;
    protected $transient;

    public function __construct()
    {
        $this->transient = range(1, 100);
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
        unset($this->transient);
    }

}