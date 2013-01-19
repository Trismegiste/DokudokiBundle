<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * AbstractMapper is an abstract Colleague for the Mediator Pattern
 * 
 * It is also a Template Method
 *
 * @author florent
 */
abstract class AbstractMapper implements Mapping
{

    protected $mediator;

    public function __construct(Mediator $ctx)
    {
        $this->mediator = $ctx;
        foreach ($this->getResponsibleType() as $name) {
            $this->mediator->registerType($name, $this);
        }
    }

    /**
     * Return an array of PHP type for which this class is responsible
     * 
     * @return array
     */
    abstract protected function getResponsibleType();
}