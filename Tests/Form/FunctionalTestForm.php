<?php

/*
 * DokudokiBundle
 */

namespace tests\Form;

use Symfony\Component\Form\Forms;
use Trismegiste\DokudokiBundle\Form\MagicFormType;

/**
 * MagicFormTypeTest is a unit test for MagicFormType parent
 *
 * @author florent
 */
class FunctionalTestForm extends \PhpUnit_Framework_TestCase
{

    /** @var \Symfony\Component\Form\FormFactory */
    protected $formFactory;

    protected function setUp()
    {
        // Set up the Form component
        $this->formFactory = Forms::createFormFactoryBuilder()
                ->addType(new MagicFormType())
                ->getFormFactory();
    }

    protected function tearDown()
    {
        unset($this->formFactory);
    }

    public function testFormServiceOk()
    {
        $this->assertNotNull($this->formFactory);
    }

}