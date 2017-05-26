<?php
 
namespace PackageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 *
 * @ORM\Table(name="package")
 * @ORM\Entity(repositoryClass="PackageBundle\Repository\PackageRepository")
 */
class Package
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", nullable=true)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="cbm", type="text", nullable=true)
     */
    private $cbm;

    /**
     * @var string
     *
     * @ORM\Column(name="marineCost", type="string", nullable=true)
     */
    private $marineCost;

    /**
     * @var string
     *
     * @ORM\Column(name="airCost", type="string", nullable=true)
     */
    private $airCost;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default" : true})
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"} )
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted_at; 

    /**
     * one prod can have Many pack.
     * @ORM\ManyToMany(targetEntity="ProductBundle\Entity\Product", mappedBy="packages")
     */
    private $product;

    public function __construct() {
        $this->Product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created_at = new \DateTime(); 
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Package
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Package
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set cbm
     *
     * @param string $cbm
     *
     * @return Package
     */
    public function setCbm($cbm)
    {
        $this->cbm = $cbm;

        return $this;
    }

    /**
     * Get cbm
     *
     * @return string
     */
    public function getCbm()
    {
        return $this->cbm;
    }

    /**
     * Set marineCost
     *
     * @param string $marineCost
     *
     * @return Package
     */
    public function setMarineCost($marineCost)
    {
        $this->marineCost = $marineCost;

        return $this;
    }

    /**
     * Get marineCost
     *
     * @return string
     */
    public function getMarineCost()
    {
        return $this->marineCost;
    }

    /**
     * Set airCost
     *
     * @param string $airCost
     *
     * @return Package
     */
    public function setAirCost($airCost)
    {
        $this->airCost = $airCost;

        return $this;
    }

    /**
     * Get airCost
     *
     * @return string
     */
    public function getAirCost()
    {
        return $this->airCost;
    }

    

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Package
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add product
     *
     * @param \ProductBundle\Entity\Product $product
     *
     * @return Package
     */
    public function addProduct(\ProductBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \ProductBundle\Entity\Product $product
     */
    public function removeProduct(\ProductBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Package
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Package
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }
}
