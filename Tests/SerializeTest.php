<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests;

/**
 * Serialize is a ...
 *
 * @author florent
 */
class SerializeTest extends \PHPUnit_Framework_TestCase
{

    public function testExperim()
    {
        $obj = new Cart("86 fdfg de fdf");
        $obj->addItem(3, new Product('EF85L', 1999));
        $obj->addItem(1, new Product('Bike', 650));

        $result = var_export($obj, true);
        $result = preg_replace('#([_A-Za-z0-9\\\\]+)::__set_state\(array\(#', '((array) array("_cls" => "$1", ', $result);

        eval('$dump = ' . $result . ';');
        $restore = instance($dump);
        $this->assertEquals($obj, $restore);
    }

}

function instance(array $param)
{
    $modeObj = isset($param['_cls']);

    if ($modeObj) {
        $fqcn = $param['_cls'];
        unset($param['_cls']);
        $factory = new \ReflectionClass($fqcn);
        $vectorOrObject = createInstanceWithoutConstructor($fqcn);
    } else {
        $vectorOrObject = array();
    }

    foreach ($param as $key => $val) {
        if (is_array($val)) {
            // go deeper
            $mapped = instance($val);
        } else {
            $mapped = $val;
        }

        // set the value
        if ($modeObj) {
            $prop = $factory->getProperty($key);
            $prop->setAccessible(true);
            $prop->setValue($vectorOrObject, $mapped);
        } else {
            $vectorOrObject[$key] = $mapped;
        }
    }

    return $vectorOrObject;
}

/**
 * Copy/Paste from http://fr2.php.net/manual/en/reflectionclass.newinstancewithoutconstructor.php
 *
 * @param type $class
 * @return type
 */
function createInstanceWithoutConstructor($class)
{
    $reflector = new \ReflectionClass($class);
    $properties = $reflector->getProperties();
    $defaults = $reflector->getDefaultProperties();

    $serealized = "O:" . strlen($class) . ":\"$class\":" . count($properties) . ':{';
    foreach ($properties as $property) {
        $name = $property->getName();
        if ($property->isProtected()) {
            $name = chr(0) . '*' . chr(0) . $name;
        } elseif ($property->isPrivate()) {
            $name = chr(0) . $class . chr(0) . $name;
        }
        $serealized .= serialize($name);
        if (array_key_exists($property->getName(), $defaults)) {
            $serealized .= serialize($defaults[$property->getName()]);
        } else {
            $serealized .= serialize(null);
        }
    }
    $serealized .="}";
    return unserialize($serealized);
}

class Cart
{

    protected $address;
    private $row = array();

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
