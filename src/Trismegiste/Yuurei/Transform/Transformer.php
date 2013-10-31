<?php

/*
 * Yuurei ◕ ‿‿ ◕
 */

namespace Trismegiste\Yuurei\Transform;

use Trismegiste\Yuurei\Transform\Mediator\RecursiveMapper;

/**
 * Factory is a transformer to move from object to array and vice versa
 *
 * @author florent
 */
class Transformer implements TransformerInterface
{

    protected $delegation;

    public function __construct(RecursiveMapper $algo)
    {
        $this->delegation = $algo;
    }

    /**
     * {@inheritDoc}
     */
    public function desegregate($obj)
    {
        if (!is_object($obj)) {
            throw new \InvalidArgumentException('Only object can be transformed into tree');
        }
        if ($obj instanceof Skippable) {
            throw new \LogicException('A root entity cannot be Skippable');
        }

        return $this->delegation->recursivDesegregate($obj);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $dump)
    {
        $obj = $this->delegation->recursivCreate($dump);
        if (gettype($obj) != 'object') {
            throw new \RuntimeException('The root entity is not an object');
            // SRP : only Mediator knows if $dump will be an object or not
        }

        return $obj;
    }

}