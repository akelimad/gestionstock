<?php
 
namespace ProviderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provider
 *
 * @ORM\Table(name="provider")
 * @ORM\Entity(repositoryClass="ProviderBundle\Repository\ProviderRepository")
 */
class Provider
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
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="moq", type="integer")
     */
    private $moq;

    /**
     * @var int
     *
     * @ORM\Column(name="productionCapacity", type="integer")
     */
    private $productionCapacity;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    /**
     * one provider can provid one or may prod.
     * ORM\@ManyToMany(targetEntity="ProductBundle:Product", mappedBy="Provider")
     */
    private $Product;

    public function __construct() {
        $this->Product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Provider
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
     * Set address
     *
     * @param string $address
     *
     * @return Provider
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Provider
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set moq
     *
     * @param integer $moq
     *
     * @return Provider
     */
    public function setMoq($moq)
    {
        $this->moq = $moq;

        return $this;
    }

    /**
     * Get moq
     *
     * @return int
     */
    public function getMoq()
    {
        return $this->moq;
    }

    /**
     * Set productionCapacity
     *
     * @param integer $productionCapacity
     *
     * @return Provider
     */
    public function setProductionCapacity($productionCapacity)
    {
        $this->productionCapacity = $productionCapacity;

        return $this;
    }

    /**
     * Get productionCapacity
     *
     * @return int
     */
    public function getProductionCapacity()
    {
        return $this->productionCapacity;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Provider
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
}
