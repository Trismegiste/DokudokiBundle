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

    public function testObjectCreation()
    {
        $form = $this->formFactory->create(new ProductType());
        $form->bind(array('title' => 'EF-85 L'));
        $obj = $form->getData();
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $obj);
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
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $obj);
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

}