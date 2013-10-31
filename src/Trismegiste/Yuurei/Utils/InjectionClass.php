<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Utils;

/**
 * InjectionClass can dynamically
 * build object and properties
 *
 * @author flo
 */
class InjectionClass extends \ReflectionClass
{

    /**
     * Set a property in an object, even it does exist in the class
     * 
     * @param object $obj
     * @param string $key
     * @param mixed $value 
     */
    public function injectProperty($obj, $key, $value)
    {
        if ($this->hasProperty($key)) {
            $prop = $this->getProperty($key);
            $prop->setAccessible(true);
            $prop->setValue($obj, $value);
        } else {
            $obj->$key = $value;
        }
    }

}
