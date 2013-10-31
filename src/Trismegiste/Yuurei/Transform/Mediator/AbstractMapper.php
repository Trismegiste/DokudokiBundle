<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Colleague (abstract)
 *
 * AbstractMapper is an abstract Colleague for the Mediator Pattern
 * Responsible : registering against the Mediator
 *
 * The subclasses declare their responsibilities and the way they make
 * the mapping by implementing the Mapping Interface
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

}