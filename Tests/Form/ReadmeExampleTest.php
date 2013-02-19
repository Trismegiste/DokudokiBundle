<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Symfony\Component\Form\Forms;
use Trismegiste\DokudokiBundle\Form\MagicFormType;

/**
 * ReadmeExample is a ...
 *
 * @author florent
 */
class ReadmeExampleTest extends FunctionalTestForm
{

    protected $formFactory;
    protected $repository;
    protected $collection;

    protected function setUp()
    {
        // Set up the Form component
        $this->formFactory = Forms::createFormFactoryBuilder()
                ->addType(new MagicFormType())
                ->getFormFactory();

        $connector = new \Trismegiste\DokudokiBundle\Tests\Persistence\ConnectorTest();
        $this->collection = $connector->testCollection();
        $facade = new \Trismegiste\DokudokiBundle\Facade\Provider($this->collection);
        $this->repository = $facade->createRepository(new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\BlackMagic());
    }

    protected function tearDown()
    {
        unset($this->formFactory);
    }

    public function testBlackMagicExample()
    {
        // construct a form
        $form = $this->formFactory
                ->createBuilder('magic_form', null, array('class_key' => 'product'))
                ->add('title')
                ->add('price')
                ->getForm();
        // bind data to th form
        $form->bind(array('title' => 'EF-85 L', 'price' => 2000));
        $doc = $form->getData();
        // getting the magic document
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $doc);
        $this->assertEquals('product', $doc->getClassName());
        $this->assertEquals('EF-85 L', $doc->getTitle());
        $this->assertEquals(2000, $doc->getPrice());
        // persistence with repository
        $this->repository->persist($doc);
        // retrieving the content in the MongoDB
        $dump = $this->collection->findOne(array('_id' => $doc->getId()));
        $this->assertEquals('product', $dump['-class']);
        $this->assertEquals('EF-85 L', $dump['title']);
        $this->assertEquals(2000, $dump['price']);
        // restoring with repository
        $restore = $this->repository->findByPk((string) $doc->getId());
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $restore);
        $this->assertEquals('product', $restore->getClassName());
        $this->assertEquals('EF-85 L', $restore->getTitle());
        $this->assertEquals(2000, $restore->getPrice());
    }

}