<?php
 
namespace ProviderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="society", type="string", length=255, nullable=true)
     */
    private $society;

    /**
     * @var string
     *
     * @ORM\Column(name="activity", type="string", length=255, nullable=true)
     */
    private $activity;


    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     * @Assert\Length(min="10", minMessage="minimum 10 chiffres")
     * @Assert\Regex(pattern="/^[0-9]{10}$/", message="juste les numero svp") 
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="rateQuality", type="integer", nullable=true)
     */
    private $rateQuality;

    /**
     * @var integer
     *
     * @ORM\Column(name="rateQualityPrice", type="integer", nullable=true)
     */
    private $rateQualityPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="rateDelivery", type="integer", nullable=true)
     */
    private $rateDelivery;

    /**
     * @var integer
     *
     * @ORM\Column(name="rateCommunication", type="integer", nullable=true)
     */
    private $rateCommunication;

    /**
     * @var integer
     *
     * @ORM\Column(name="ratePartnership", type="integer", nullable=true)
     */
    private $ratePartnership;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true,options={"default" : true})
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
     * @ORM\Column(name="deleted_at", type="datetime" , nullable=true)
     */
    private $deleted_at; 

    /**
     * Many cat have Many prod.
     * @ORM\ManyToMany(targetEntity="ProductBundle\Entity\Product", mappedBy="providers")
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

    /**
     * Add product
     *
     * @param \ProductBundle\Entity\Product $product
     *
     * @return Provider
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
     * @return Provider
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
     * @return Provider
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

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Provider
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set society
     *
     * @param string $society
     *
     * @return Provider
     */
    public function setSociety($society)
    {
        $this->society = $society;

        return $this;
    }

    /**
     * Get society
     *
     * @return string
     */
    public function getSociety()
    {
        return $this->society;
    }

    /**
     * Set activity
     *
     * @param string $activity
     *
     * @return Provider
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Provider
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Provider
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set rateQuality
     *
     * @param integer $rateQuality
     *
     * @return Provider
     */
    public function setRateQuality($rateQuality)
    {
        $this->rateQuality = $rateQuality;

        return $this;
    }

    /**
     * Get rateQuality
     *
     * @return integer
     */
    public function getRateQuality()
    {
        return $this->rateQuality;
    }

    /**
     * Set rateQualityPrice
     *
     * @param integer $rateQualityPrice
     *
     * @return Provider
     */
    public function setRateQualityPrice($rateQualityPrice)
    {
        $this->rateQualityPrice = $rateQualityPrice;

        return $this;
    }

    /**
     * Get rateQualityPrice
     *
     * @return integer
     */
    public function getRateQualityPrice()
    {
        return $this->rateQualityPrice;
    }

    /**
     * Set rateDelivery
     *
     * @param integer $rateDelivery
     *
     * @return Provider
     */
    public function setRateDelivery($rateDelivery)
    {
        $this->rateDelivery = $rateDelivery;

        return $this;
    }

    /**
     * Get rateDelivery
     *
     * @return integer
     */
    public function getRateDelivery()
    {
        return $this->rateDelivery;
    }

    /**
     * Set rateCommunication
     *
     * @param integer $rateCommunication
     *
     * @return Provider
     */
    public function setRateCommunication($rateCommunication)
    {
        $this->rateCommunication = $rateCommunication;

        return $this;
    }

    /**
     * Get rateCommunication
     *
     * @return integer
     */
    public function getRateCommunication()
    {
        return $this->rateCommunication;
    }

    /**
     * Set ratePartnership
     *
     * @param integer $ratePartnership
     *
     * @return Provider
     */
    public function setRatePartnership($ratePartnership)
    {
        $this->ratePartnership = $ratePartnership;

        return $this;
    }

    /**
     * Get ratePartnership
     *
     * @return integer
     */
    public function getRatePartnership()
    {
        return $this->ratePartnership;
    }
}
