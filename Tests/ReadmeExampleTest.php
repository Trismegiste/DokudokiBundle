<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form {

    use Symfony\Component\Form\Forms,
        Trismegiste\DokudokiBundle\Form\MagicFormType,
        Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

    /**
     * ReadmeExample is a ...
     *
     * @author florent
     */
    class ReadmeExampleTest extends FunctionalTestForm
    {

        protected $formFactory;
        protected $blackmagic;
        protected $whitemagic;
        protected $invocation;
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
            $this->blackmagic = $facade->createRepository(new Stage\BlackMagic());
            $this->invocation = $facade->createRepository(new Stage\Invocation());
            $this->whitemagic = $facade->createRepository(new Stage\WhiteMagic(array('product' => 'Some\Sample\Product')));
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
            // persistence with blackmagic repository
            $this->blackmagic->persist($doc);
            // retrieving the content in the MongoDB
            $dump = $this->collection->findOne(array('_id' => $doc->getId()));
            $this->assertEquals('product', $dump['-class']);
            $this->assertEquals('EF-85 L', $dump['title']);
            // restoring with blackmagic repository
            $restore = $this->blackmagic->findByPk((string) $doc->getId());
            $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $restore);
            $this->assertEquals('product', $restore->getClassName());
            $this->assertEquals('EF-85 L', $restore->getTitle());
        }

        public function testInvocationExample()
        {
            // simple object
            $doc = new \Some\Sample\Product('EF-85 L', 2000);
            // persisting
            $this->invocation->persist($doc);
            // restoring with invocation repository
            $restore = $this->invocation->findByPk((string) $doc->getId());
            $this->assertInstanceOf('Some\Sample\Product', $restore);
            // retrieving the content in the MongoDB
            $dump = $this->collection->findOne(array('_id' => $doc->getId()));
            $this->assertEquals('Some\Sample\Product', $dump['-fqcn']);
            $this->assertEquals('EF-85 L', $dump['title']);
            $this->assertEquals(2000, $dump['price']);
        }

        public function testWhiteMagicExample()
        {
            // simple object
            $doc = new \Some\Sample\Product('EF-85 L', 2000);
            // persisting
            $this->whitemagic->persist($doc);
            // restoring with whitemagic repository
            $restore = $this->whitemagic->findByPk((string) $doc->getId());
            $this->assertInstanceOf('Some\Sample\Product', $restore);
            // retrieving the content in the MongoDB
            $dump = $this->collection->findOne(array('_id' => $doc->getId()));
            $this->assertEquals('product', $dump['-class']);
            $this->assertEquals('EF-85 L', $dump['title']);
            $this->assertEquals(2000, $dump['price']);
        }

    }

}

namespace Some\Sample {

    use Trismegiste\DokudokiBundle\Persistence\Persistable;

    class Product implements Persistable
    {

        protected $id;
        protected $title;
        protected $price;

        public function __construct($title, $price)
        {
            $this->title = $title;
            $this->price = $price;
        }

        public function getId()
        {
            return $this->id;
        }

        public function setId(\MongoId $pk)
        {
            $this->id = $pk;
        }

    }

}