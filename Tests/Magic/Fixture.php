<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Magic;

use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * Fixture provides test data because
 * for clarity I don't want this in test case
 *
 * @author florent
 */
class Fixture
{

    public function getFullTreeObject()
    {
        return new Document(array(
                    Document::classKey => 'cart',
                    'customer' => new Document(array(Document::classKey => 'person', 'name' => 'Wayne')),
                    'row' => array(
                        array(
                            'qt' => 2,
                            'item' => new Document(array(
                                Document::classKey => 'product',
                                'price' => 2000,
                                'title' => 'EF-85L'
                            ))
                        ),
                        array(
                            'qt' => 3,
                            'item' => new Document(array(
                                Document::classKey => 'product',
                                'price' => 680,
                                'title' => 'bicycle',
                                'color' => new Document(array(
                                    Document::classKey => 'option',
                                    'choice' => 'white'
                                ))
                            ))
                        )
                    ),
                    'state' => array(1, 2, 3)
                ));
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