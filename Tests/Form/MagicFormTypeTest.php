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

    protected function getEmbeddedEdition()
    {
        $orig = $this->buildDoc('cart', array('address' => 'Bradbury apartments, ninth sector. NM46751'));
        $orig->setProduct1($this->buildDoc('product', array('title' => 'EF-85 L', 'price' => 1999)));
        $cart = $this->buildDoc('cart', array('address' => 'Bradbury apartments, ninth sector. NM46751'));
        $cart->setProduct1($this->buildDoc('product', array('title' => 'VTOL', 'price' => 10000)));
        return array(null, new CartType(), array(
                'address' => 'Bradbury apartments, ninth sector. NM46751',
                'product1' => array('title' => 'VTOL', 'price' => 10000),
                'title' => 'not used'
            ),
            $cart
        );
    }

    public function getChildCreation()
    {
        $result = $this->buildDoc('cart', array('address' => 'Bradbury apartments, ninth sector. NM46751'));
        $result->setRow(
                array(
                    array('qt' => 3, 'item' => $this->buildDoc('product', array('title' => 'EF-85 L', 'price' => 1999)))
                )
        );

        return array(null, new CartPlusType(), array(
                'address' => 'Bradbury apartments, ninth sector. NM46751',
                'row' => array(
                    array(
                        'qt' => 3,
                        'item' => array('title' => 'EF-85 L', 'price' => 1999)
                    )
            )),
            $result);
    }

    public function getConfigBinding()
    {
        return array(
            $this->getSimpleCreation(),
            $this->getSimpleEdition(),
            $this->getAddEdition(),
            $this->getEmbeddedCreation(),
            $this->getEmbeddedEdition(),
            $this->getChildCreation()
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