<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Colleague (abstract)
 *
 * AbstractMapper is an abstract Colleague for the Mediator Pattern
 * Responsible : registering against the Mediator
 * It is also a Template Method Pattern
 *
 * The subclasses declare their type and the way they do the mapping by
 * implementing the Mapping Interface
 *
 * @author florent
 */
abstract class AbstractMapper implements Mapping
{

    protected $mediator;

    public function __construct(TypeRegistry $ctx)
    {
        $this->mediator = $ctx;
        $this->mediator->registerType($this);
    }

    /**
     * Return an array of PHP type for which this class is responsible
     *
     * @return array
     */
    abstract public function isResponsibleFromDb($var);

    /**
     * Return an array of PHP type for which this class is responsible
     *
     * @return array
     */
    abstract public function isResponsibleToDb($var);
}