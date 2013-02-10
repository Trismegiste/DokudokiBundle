<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Magic;

use Trismegiste\DokudokiBundle\Persistence\Persistable;

/**
 * Document is a container with automatic getters & setters
 *
 * Here is an example:
 * <pre><code>
 * <?php
 * $doc = new Document();
 * $doc->setAnswser(42);
 * echo $doc->getAnswer();
 * ?>
 * </code></pre>
 *
 * Why final ?
 * Because Php Magic is good but with inheritance it can become a mess.
 * If you have time for subclassing a magic document and put it in your model, 
 * I suggest you'd better create a real class.
 */
final class Document extends InternalContent implements Persistable
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

    public function setId(\MongoId $pk)
    {
        $this->setter('id', array($pk));
    }

    public function getId()
    {
        return $this->has('id') ? $this->getter('id', array()) : null;
    }

}
