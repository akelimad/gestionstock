<?php

namespace ProductBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
	public function getAllProducts()
    {
        return $this->getEntityManager()->createQuery("
        	SELECT p FROM ProductBundle:Product p where p NOT IN (select p1 from ProductBundle:Product p1, ProductBundle:ProductLog pl
            WHERE p1.id=pl.product AND pl.deleted_at IS NOT NULL )
        ")->getResult();
    }
	
}
