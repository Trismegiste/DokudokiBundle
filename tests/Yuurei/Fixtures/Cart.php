<?php

/*
 * Dokudoki
 */

namespace tests\Yuurei\Fixtures;

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

    public function addItem($qt, $pro)
    {
        $this->row[] = array('qt' => $qt, 'item' => $pro);
    }

}
