<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\Yuurei\Persistence;

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
