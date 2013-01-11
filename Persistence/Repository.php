<?php

namespace Trismegiste\DokudokiBundle\Persistence;

use Trismegiste\MongoSapinBundle\Model\FactoryMethod;

/**
 * Repository of Document
 *
 * @author flo
 */
class Repository implements RepositoryInterface
{

    protected $collection;
    protected $factory;

    public function __construct(\MongoCollection $coll, FactoryMethod $fac)
    {
        $this->collection = $coll;
        $this->factory = $fac;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Automagic $doc)
    {
        $struc = $doc->getUnTyped();
        if (array_key_exists('id', $struc)) {
            $struc['_id'] = $struc['id'];
            unset($struc['id']);
        }
        $struc['_timestamp'] = time();
      //  $struc['_keyword'] = $this->stemming($struc);
        $this->collection->save($struc);
        $doc->setId($struc['_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function findByPk($pk)
    {
        $id = new \MongoId($pk);
        $struc = $this->collection->findOne(array('_id' => $id));
        if (is_null($struc)) {
            throw new NotFoundException($pk);
        }
        $struc['id'] = $struc['_id'];
        unset($struc['_id'], $struc['_timestamp']);
        $obj = $this->factory->create($struc);

        return $obj;
    }

    /**
     * do a a stemming on a tree
     *
     * @param array $tab
     * @return array
     */
    protected function stemming(array $tab, $minLen = 4, $maxLen = 128)
    {
        $keyword = array();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($tab));
        foreach ($iterator as $key => $val) {
            if ($key[0] == '_') {
                continue;
            }
            if (strlen($val) < $maxLen) {
                $words = explode(' ', $val);
                foreach ($words as $item) {
                    $len = strlen($item);
                    if ($len >= $minLen) {
                        $keyword[] = $item;
                    }
                }
            }
        }

        return $keyword;
    }

}
