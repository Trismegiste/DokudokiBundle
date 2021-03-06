<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Persistence;

use Trismegiste\Yuurei\Persistence\Decorator;
use Trismegiste\Yuurei\Persistence\RepositoryInterface;

/**
 * MultipleClassDecorator is a concrete decorator for repository with a filter on
 * one multiple classes. Very convinient for service declaration.
 */
class MultipleClassDecorator extends Decorator
{

    protected $fqcn;
    protected $key;

    public function __construct(RepositoryInterface $wrapped, $key, array $fqcn)
    {
        parent::__construct($wrapped);
        $this->key = $key;
        $this->fqcn = $fqcn;
    }

    public function find(array $query = array())
    {
        $query[$this->key] = ['$in' => $this->fqcn];

        return $this->decorated->find($query);
    }

    public function findOne(array $query = array())
    {
        $query[$this->key] = ['$in' => $this->fqcn];

        return $this->decorated->findOne($query);
    }

    public function getCursor(array $query = array(), array $fields = array())
    {
        $query[$this->key] = ['$in' => $this->fqcn];

        return $this->decorated->getCursor($query, $fields);
    }

}