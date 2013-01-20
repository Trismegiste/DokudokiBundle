<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Transform\Tests;

use Trismegiste\DokudokiBundle\Transform\Skippable;
use Trismegiste\DokudokiBundle\Transform\Cleanable;

class Cart
{

    public $info = '';
    protected $address;
    private $row = array();
    protected $notInitialized;
    static public $transientProp = 123;

    public function __construct($addr)
    {
        $this->address = $addr;
    }

    public function addItem($qt, Product $pro)
    {
        $this->row[] = array('qt' => $qt, 'item' => $pro);
    }

}

class Product
{

    private $title;
    protected $price = 0;

    public function __construct($tit, $pri)
    {
        $this->title = $tit;
        $this->price = $pri;
    }

}

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