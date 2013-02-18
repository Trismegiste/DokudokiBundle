<?php

/**
 * Template for generating a class with properies, getters and setters
 * in camelCase.
 */
echo '<?php';
?>
namespace <?php echo $classNamespace ?>;

use Trismegiste\DokudokiBundle\Persistence\Persistable;

/**
 *  <?php echo $className ?> is a Persistable class
 */
class <?php echo $className ?> implements Persistable
{
    protected $id;
    <?php foreach($property as $item) : ?>
    protected $<?php echo $item ?>;
    <?php endforeach; ?>

    public function getId()
    {
        return $this->id;
    }
    
    public function setId(\MongoId $pk)
    {
        $this->id = $pk;
    }
    
    <?php foreach($property as $item) : ?>
    public function get<?php echo ucfirst($item) ?>()
    {
        return $this-><?php echo $item ?>;
    }
    
    public function set<?php echo ucfirst($item) ?>($var)
    {
        $this-><?php echo $item ?> = $var;
    }
    <?php endforeach; ?>

}
