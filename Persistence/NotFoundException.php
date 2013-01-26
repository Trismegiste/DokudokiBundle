<?php

/**
 * Persistence
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * NotFoundException if an exception thrown when no document was found
 *
 * @author flo
 */
class NotFoundException extends \RuntimeException
{

    /**
     * constructor
     * 
     * @param string $pk the pk not found in database
     */
    public function __construct($pk)
    {
        parent::__construct("$pk was not found");
    }

}
