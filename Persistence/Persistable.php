<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 *
 * @author flo
 */
interface Persistable
{

    function getId();

    function setId(\MongoId $pk);
}
