<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Trismegiste\DokudokiBundle\Tests\Form\ProductType;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * MagicFormTypeTest is a unit test for MagicFormType parent
 *
 * @author florent
 */
class MagicFormTypeTest extends FunctionalTestForm
{

    static protected $magicDocummentClassName = 'Trismegiste\DokudokiBundle\Magic\Document';

    public function testObjectCreation()
    {
        $form = $this->formFactory->create(new ProductType());
        $form->bind(array('title' => 'EF-85 L'));
        $obj = $form->getData();
        $this->assertInstanceOf(static::$magicDocummentClassName, $obj);
        $this->assertEquals('product', $obj->getClassname());
        $this->assertEquals('EF-85 L', $obj->getTitle());
    }

    public function testObjectEdition()
    {
        $obj = new Document('product');
        $obj->setTitle('EOS 7D');
        $this->assertEquals('EOS 7D', $obj->getTitle());
        $form = $this->formFactory->create(new ProductType(), $obj);
        $form->bind(array('title' => 'EF-85 L'));
        $this->assertInstanceOf(static::$magicDocummentClassName, $obj);
        $this->assertEquals('product', $obj->getClassname());
        $this->assertEquals('EF-85 L', $obj->getTitle());
    }

    public function testObjectEditionAddUnchanged()
    {
        $obj = new Document('product');
        $obj->setTitle('EOS 7D');
        $obj->setDescription('Bokehlicious');
        $form = $this->formFactory->create(new ProductType(), $obj);
        $form->bind(array('title' => 'EF-85 L'));
        $this->assertEquals('EF-85 L', $obj->getTitle());
        $this->assertEquals('Bokehlicious', $obj->getDescription());
    }

    public function testEmbeddedObjectCreation()
    {
        $form = $this->formFactory->create(new CartType());
        $form->bind(array(
            'address' => 'Bradbury apartments, ninth sector. NM46751',
            'product1' => array('title' => 'EF-85 L', 'price' => 1999)
        ));
        $obj = $form->getData();
        $this->assertInstanceOf(static::$magicDocummentClassName, $obj);
        $this->assertEquals('cart', $obj->getClassname());
        $this->assertStringStartsWith('Bradbury', $obj->getAddress());
        $this->assertInstanceOf(static::$magicDocummentClassName, $obj->getProduct1());
        $this->assertEquals(1999, $obj->getProduct1()->getPrice());
    }

    public function testChildObjectEdition()
    {
        $obj = new Document('product');
        $obj->setTitle('EOS 5D mk III');
        $cart = new Document('cart');
        $cart->setProduct1($obj);
        $form = $this->formFactory->create(new CartType(), $cart);
        $form->bind(array(
            'address' => 'Bradbury apartments, ninth sector. NM46751',
            'title' => 'wesh',
            'product1' => array('title' => 'EF-35 L')
        ));
        $data = $form->getData();
        print_r($data);
        $this->assertStringStartsWith('Bradbury', $data->getAddress());
        $this->assertInstanceOf(static::$magicDocummentClassName, $data->getProduct1());
        $this->assertEquals('product', $data->getProduct1()->getClassname());
        $this->assertEquals('EF-35 L', $data->getProduct1()->getTitle());
    }

}