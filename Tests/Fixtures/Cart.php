<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

class Cart
{

    public $info = '';
    protected $address;
    protected $row = array();
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
