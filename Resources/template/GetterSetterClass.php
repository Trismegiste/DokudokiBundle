<?php

/**
 * Template for generating a class with properies, getters and setters
 * in camelCase.
 */
echo '<?php';
?>
namespace <?php echo $classNamespace ?>;

/**
 *  <?php echo $className ?> is 
 */
class <?php echo $className ?>
{

    <?php foreach($property as $item) : ?>
    protected $<?php echo $item ?>;
    <?php endforeach; ?>
    
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
