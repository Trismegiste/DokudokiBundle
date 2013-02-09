<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Facade;

use Trismegiste\DokudokiBundle\Transform\Delegation\MappingBuilder;

/**
 * Design Pattern: Factory Method
 * ProviderInterface is a contract for providing repository
 * 
 * @author flo
 */
interface ProviderInterface
{

    /**
     * Creates a new instance of a repository with a builder for the mapping
     * 
     * @param MappingBuilder $builder the builder of mapping
     * 
     * @return Trismegiste\DokudokiBundle\Persistence\RepositoryInterface
     */
    function createRepository(MappingBuilder $builder);
}