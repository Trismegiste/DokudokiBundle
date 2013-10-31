<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Persistence;

use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * Test repository with Hoodoo stage
 *
 * @author flo
 */
class RepositoryHoodooTest extends RepositoryWhiteMagicTest
{

    protected function createBuilder()
    {
        $fixture = 'tests\Yuurei\Fixtures';
        $alias = array(
            'simple' => $fixture . '\Simple',
            'Order' => $fixture . '\Order',
            'Product' => $fixture . '\Product',
            'NonTrivial' => $fixture . '\VerifMethod',
            'Hibernate' => $fixture . '\Bear',
        );
        return new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Hoodoo($alias);
    }

    public function getComplexObject()
    {
        $data = parent::getComplexObject();
        list( $obj, $dump) = $data[0];
        // injecting a magic document in the cart
        $product = new Document('unicorn');
        $obj->addItem(2, $product);
        $product->setMatter('paper');
        $dump['row'][] = array(
            'qt' => 2,
            'item' => array(Document::classKey => 'unicorn', 'matter' => 'paper')
        );

        return array(array($obj, $dump));
    }

}
