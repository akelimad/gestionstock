<?php

namespace CategoryBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{

    public function getAllCategories()
    {
        return $this->getEntityManager()->createQuery("
        	SELECT c FROM CategoryBundle:Category c 
            WHERE c.deleted_at IS NULL  ORDER BY c.name ASC
        ")->getResult();
    }

    public function getAllRootCat()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM CategoryBundle:Category c WHERE c.parent IS NULL '
            )->getResult();
    }

    public function getAllSubCat($id)
    {

        var_dump($id);die;
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM CategoryBundle:Category c WHERE c.parent = :id'
            )->setParameter(':id', $id)->getResult();

    }

    

}
