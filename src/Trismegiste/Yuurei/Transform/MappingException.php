<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\Yuurei\Transform;

/**
 * MappingException is an exception when mapping fails
 *
 */
class MappingException extends \RuntimeException
{

    public function __construct($var, $ctx)
    {
        $type = gettype($var);
        if ($type === 'object') {
            $type = get_class($var);
        }
        $msg = sprintf("Cannot map '%s' during %s", $type, $ctx);
        parent::__construct($msg);
    }

}