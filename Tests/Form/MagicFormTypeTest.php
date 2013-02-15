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
        $this->assertStringStartsWith('Bradbury', $data->getAddress());
        $this->assertInstanceOf(static::$magicDocummentClassName, $data->getProduct1());
        $this->assertEquals('product', $data->getProduct1()->getClassname());
        $this->assertEquals('EF-35 L', $data->getProduct1()->getTitle());
    }

    public function testMixedEmbeddedObjectCreation()
    {
        $form = $this->formFactory->create(new CartPlusType());
        $form->bind(array(
            'address' => 'Bradbury apartments, ninth sector. NM46751',
            'row' => array(
                array(
                    'qt' => 3,
                    'item' => array('title' => 'EF-85 L', 'price' => 1999)
                )
            )
        ));
        $obj = $form->getData();
        $product = new Document('product');
        $product->setTitle('EF-85 L');
        $product->setPrice(1999);
        $cart = new Document('cart');
        $cart->setAddress('Bradbury apartments, ninth sector. NM46751');
        $cart->setRow(
                array(
                    array('qt' => 3, 'item' => $product)
                )
        );
        $this->assertEquals($cart, $obj);
    }

    protected function buildDoc($class, $data)
    {
        $obj = new Document($class);
        foreach ($data as $key => $val) {
            call_user_func(array($obj, 'set' . ucfirst($key)), $val);
        }

        return $obj;
    }

    protected function getSimpleCreation()
    {
        $result = $this->buildDoc('product', array('title' => 'EOS 1DX', 'price' => null));

        return array(null, new ProductType(), array('title' => 'EOS 1DX'), $result);
    }

    protected function getSimpleEdition()
    {
        $origin = $this->buildDoc('product', array('title' => 'EOS 1DX', 'price' => 7000));
        $result = $this->buildDoc('product', array('title' => 'EF-85 L', 'price' => 2000));

        return array($origin, new ProductType(), array('title' => 'EF-85 L', 'price' => 2000), $result);
    }

    protected function getAddEdition()
    {
        $origin = $this->buildDoc('product', array('title' => 'EOS 1DX', 'description' => 'lorem'));
        $result = $this->buildDoc('product', array('title' => 'EOS 1DX', 'price' => null, 'description' => 'lorem'));

        return array($origin, new ProductType(), array('title' => 'EOS 1DX'), $result);
    }

    protected function getEmbeddedCreation()
    {
        $cart = $this->buildDoc('cart', array('address' => 'Bradbury apartments, ninth sector. NM46751'));
        $cart->setProduct1($this->buildDoc('product', array('title' => 'EF-85 L', 'price' => 1999)));
        return array(null, new CartType(), array(
                'address' => 'Bradbury apartments, ninth sector. NM46751',
                'product1' => array('title' => 'EF-85 L', 'price' => 1999)
            ),
            $cart
        );
    }

    public function getConfigBinding()
    {
        return array(
            $this->getSimpleCreation(),
            $this->getSimpleEdition(),
            $this->getAddEdition(),
            $this->getEmbeddedCreation()
        );
    }

    /**
     * @dataProvider getConfigBinding
     */
    public function testBindingForm($before, \Symfony\Component\Form\AbstractType $typeForm, $input, $after)
    {
        $form = $this->formFactory->create($typeForm, $before);
        $form->bind($input);
        $obj = $form->getData();
        $this->assertInstanceOf(static::$magicDocummentClassName, $obj);
        $this->assertNotEmpty($obj->getClassname());
        $this->assertEquals($after, $obj);
    }

}