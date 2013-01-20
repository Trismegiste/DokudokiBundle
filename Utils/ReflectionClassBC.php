<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Utils;

/**
 * Backward compatiblity with PHP version < 5.4
 *
 * @author flo
 */
class ReflectionClassBC extends \ReflectionClass
{

    /**
     * Fix for PHP version < 5.4
     * 
     * @return object a new instance of this class
     */
    public function newInstanceWithoutConstructor()
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return parent::newInstanceWithoutConstructor();
        } else {
            $class = $this->getName();
            return unserialize(sprintf('O:%d:"%s":0:{}', strlen($class), $class));
        }
    }

    public function fixHackBC($obj)
    {
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            if ($this->hasMethod('__wakeup')) {
                $obj->__wakeup();
            }
        }
    }

}
