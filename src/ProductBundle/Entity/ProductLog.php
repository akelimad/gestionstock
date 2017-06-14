<?php
namespace ProductBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * ProductLog
 *
 * @ORM\Table(name="product_log")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductLogRepository")
 */
class ProductLog
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
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"} )
     */
    private $updated_at;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime" , nullable=true)
     */
    private $deleted_at; 

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="user_log")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_log") 
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="unitPrice", type="string", length=255,nullable=true)
     */
    private $unitPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="wholesalePrice", type="string", length=255,nullable=true)
     */
    private $wholesalePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="specialPrice", type="string", length=255,nullable=true)
     */
    private $specialPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="internetPrice", type="string", length=255,nullable=true)
     */
    private $internetPrice;


    public function __construct()
    {
        $this->updated_at = new \DateTime(); 
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
     * Set productId
     *
     * @param integer $productId
     *
     * @return ProductLog
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }
    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }
    /**
     * Set action
     *
     * @param string $action
     *
     * @return ProductLog
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    
    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return ProductLog
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }
    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Set product
     *
     * @param \ProductBundle\Entity\Product $product
     *
     * @return ProductLog
     */
    public function setProduct(\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;
        return $this;
    }
    /**
     * Get product
     *
     * @return \ProductBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ProductLog
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return ProductLog
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
     * Set unitPrice
     *
     * @param string $unitPrice
     *
     * @return ProductLog
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set wholesalePrice
     *
     * @param string $wholesalePrice
     *
     * @return ProductLog
     */
    public function setWholesalePrice($wholesalePrice)
    {
        $this->wholesalePrice = $wholesalePrice;

        return $this;
    }

    /**
     * Get wholesalePrice
     *
     * @return string
     */
    public function getWholesalePrice()
    {
        return $this->wholesalePrice;
    }

    /**
     * Set specialPrice
     *
     * @param string $specialPrice
     *
     * @return ProductLog
     */
    public function setSpecialPrice($specialPrice)
    {
        $this->specialPrice = $specialPrice;

        return $this;
    }

    /**
     * Get specialPrice
     *
     * @return string
     */
    public function getSpecialPrice()
    {
        return $this->specialPrice;
    }

    /**
     * Set internetPrice
     *
     * @param string $internetPrice
     *
     * @return ProductLog
     */
    public function setInternetPrice($internetPrice)
    {
        $this->internetPrice = $internetPrice;

        return $this;
    }

    /**
     * Get internetPrice
     *
     * @return string
     */
    public function getInternetPrice()
    {
        return $this->internetPrice;
    }
}
