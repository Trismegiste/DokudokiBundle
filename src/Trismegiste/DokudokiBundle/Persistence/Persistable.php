<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * Means this object has a primary key
 * 
 * @author flo
 */
interface Persistable
{

    function getId();

    function setId(\MongoId $pk);
}
