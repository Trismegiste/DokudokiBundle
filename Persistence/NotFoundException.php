<?php

/**
 * Persistence
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * Description of NotFoundException
 *
 * @author flo
 */
class NotFoundException extends \RuntimeException
{

    public function __construct($pk)
    {
        parent::__construct("$pk was not found");
    }

}
