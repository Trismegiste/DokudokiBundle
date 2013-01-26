<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Magic;

/**
 * Document is a container with automatic getters & setters
 *
 * Here is an example:
 * <pre><code>
 * <?php
 * $doc = new Document();
 * $doc->setAnswser(42);
 * echo $doc->getAnswer();
 * print_r($doc->getUnTyped());
 * ?>
 * </code></pre>
 *
 * @author flo
 */
class Document extends InternalContent
{

    /**
     * Implements getter and setter
     *
     * @param string $methodName (get|set)MyProperty
     * @param array $arg
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException if the nimber of parameters is not good
     * @throws \DomainException if the property does not exists
     * @throws \BadMethodCallException if the method name is bad (camelCase only)
     */
    public function __call($methodName, $arg)
    {
        if (preg_match('#^(get|set)([A-Z][A-Za-z0-9]*)$#', $methodName, $extract)) {
            $propName = lcfirst($extract[2]);
            switch ($extract[1]) {
                case 'set' :
                    $this->setter($propName, $arg);
                    break;
                case 'get' :
                    return $this->getter($propName, $arg);
                    break;
            }
        } else {
            throw new \BadMethodCallException("Method $methodName is unknown");
        }
    }

}
