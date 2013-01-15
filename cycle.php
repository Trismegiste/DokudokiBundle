<?php

class Father
{

    public $tab = array(1, 2.5, true);
    public $child;

}

class Child
{

    public $parent;
    public $itself;

    public function __construct()
    {
        $this->itself = $this;
    }

}

function transform($dump)
{
    preg_match('#^O:(\d+):(.+)$#', $dump, $match);
    $classname = $match[1];
    preg_match('#^".{' . $classname . '}":(\d+):\{(.+)\}$#', $match[2], $content);
    $nb = $content[1];

    return $content;
}

$father = new Father();
$child = new Child();
$child->parent = $father;
$father->child = $child;

$dump = serialize($father);
print_r($dump);
print_r(transform($dump));