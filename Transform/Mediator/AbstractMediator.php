<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Mediator (abstract)
 *
 * Responsible for maintaining a list of Colleague
 *
 * @author flo
 */
abstract class AbstractMediator implements RecursiveMapper, TypeRegistry
{

    protected $mappingColleague = array();

    /**
     * {@inheritDoc}
     *
     * You must note that order of registering matters :
     * The first colleague which said "I'll do it" will be the first
     * to work no matter others colleague.
     */
    public function registerType(Mapping $colleague)
    {
        $this->mappingColleague[] = $colleague;
    }

}
