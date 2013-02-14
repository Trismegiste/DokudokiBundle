<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Trismegiste\DokudokiBundle\Tests\Form\ProductType;

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

}