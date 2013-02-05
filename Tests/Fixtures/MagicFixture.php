<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * Fixture provides test data because
 * for clarity I don't want this in test case
 *
 * @author florent
 */
class MagicFixture
{

    public function getProduct($title, $price, $color = null)
    {
        $obj = new Document('product');
        $obj->setPrice($price);
        $obj->setTitle($title);
        if (!is_null($color)) {
            $obj->setColor($color);
        }

        return $obj;
    }

    public function getPerson($name)
    {
        $person = new Document('person');
        $person->setName($name);

        return $person;
    }

    public function getOption($title)
    {
        $obj = new Document('option');
        $obj->setChoice($title);

        return $obj;
    }

    public function getFullTreeObject()
    {
        $cart = new Document('cart');
        $cart->setCustomer($this->getPerson('Wayne'));
        $cart->setRow(
                array(
                    array(
                        'qt' => 2,
                        'item' => $this->getProduct('EF-85L', 2000)
                    ),
                    array(
                        'qt' => 3,
                        'item' => $this->getProduct('bicycle', 680, $this->getOption('white'))
                    )
                )
        );
        $cart->setState(array(1, 2, 3));

        return $cart;
    }

    public function getFullTreeFlat()
    {
        return array(
            Document::classKey => 'cart',
            'customer' => array(
                Document::classKey => 'person',
                'name' => 'Wayne',
            ),
            'row' => array(
                0 => array(
                    'qt' => 2,
                    'item' => array(
                        Document::classKey => 'product',
                        'price' => 2000,
                        'title' => 'EF-85L',
                    ),
                ),
                1 => array(
                    'qt' => 3,
                    'item' => array(
                        Document::classKey => 'product',
                        'price' => 680,
                        'title' => 'bicycle',
                        'color' => array(
                            Document::classKey => 'option',
                            'choice' => 'white',
                        )
                    )
                )
            ),
            'state' => array(
                0 => 1,
                1 => 2,
                2 => 3,
            )
        );
    }

}