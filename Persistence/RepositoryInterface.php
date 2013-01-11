<?php

/**
 * Persistence
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * A template for a repository
 * 
 * @author flo
 */
interface RepositoryInterface
{

    /**
     * Transforms an object tree into a tree/array and persists it into
     * @param type $doc
     */
    function persist($doc);

    /**
     * Creates an instance and maps this object with data retrieved from the
     * database for the primary key
     *
     * @param string $name the primary key
     *
     * @throws NotFoundException When no object found for this pk
     */
    function findByPk($id);
}
