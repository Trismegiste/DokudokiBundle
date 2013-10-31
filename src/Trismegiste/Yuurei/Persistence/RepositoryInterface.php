<?php

/**
 * Persistence
 */

namespace Trismegiste\Yuurei\Persistence;

/**
 * A template for a repository
 * 
 * @author flo
 */
interface RepositoryInterface
{

    /**
     * Transforms an object tree into a tree/array and persists it 
     * into a database layer
     * 
     * @param Persistable $doc
     */
    function persist(Persistable $doc);

    /**
     * Finds an object from the database for a given primary key and
     * maps it with a transformer into a real object.
     *
     * @param string $id the primary key
     * 
     * @return Persistable
     *
     * @throws NotFoundException When no object found for this pk
     */
    function findByPk($id);

    /**
     * Creates an instance and maps this object with data retrieved from 
     * database. Usefull when using MongoCollection::find
     * 
     * @param array $struc a raw structure coming from database
     * 
     * @return Persistable
     * 
     */
    function createFromDb(array $struc);
}
