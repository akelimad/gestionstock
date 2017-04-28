<?php
 
namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="sizeInch", type="decimal", precision=10, scale=0)
     */
    private $sizeInch;

    /**
     * @var string
     *
     * @ORM\Column(name="sizeCm", type="decimal", precision=10, scale=0)
     */
    private $sizeCm;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="composition", type="text")
     */
    private $composition;

    /**
     * @var string
     *
     * @ORM\Column(name="form", type="text")
     */
    private $form;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="decimal", precision=10, scale=0)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="unitPrice", type="decimal", precision=10, scale=0)
     */
    private $unitPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="wholesalePrice", type="decimal", precision=10, scale=0)
     */
    private $wholesalePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="specialPrice", type="decimal", precision=10, scale=0)
     */
    private $specialPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="internetPrice", type="decimal", precision=10, scale=0)
     */
    private $internetPrice;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;




    /**
     * One Product has Many images.
     * @ORM\OneToMany(targetEntity="ProductLog", mappedBy="user")
     */
    private $user;

    /**
     * One Product has Many images.
     * @ORM\OneToMany(targetEntity="ImageProduct", mappedBy="product")
     */
    private $imagesProduct;

    /**
     * One Product belongs to many category.
     * @ORM\ManyToMany(targetEntity="CategoryBundle\Entity\Category", inversedBy="Product")
     * @ORM\JoinTable(name="category_product")
     */
    private $category;

    /**
     * One Product belongs to many package.
     * @ORM\ManyToMany(targetEntity="PackageBundle\Entity\Package", inversedBy="Product")
     * @ORM\JoinTable(name="package_product")
     */
    private $package;

    /**
     * One Product belongs to many provider.
     * @ORM\ManyToMany(targetEntity="ProviderBundle\Entity\Provider", inversedBy="Product")
     * @ORM\JoinTable(name="provider_product")
     */
    private $provider;


    public function __construct() {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->package = new \Doctrine\Common\Collections\ArrayCollection();
        $this->provider = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set sizeInch
     *
     * @param string $sizeInch
     *
     * @return Product
     */
    public function setSizeInch($sizeInch)
    {
        $this->sizeInch = $sizeInch;

        return $this;
    }

    /**
     * Get sizeInch
     *
     * @return string
     */
    public function getSizeInch()
    {
        return $this->sizeInch;
    }

    /**
     * Set sizeCm
     *
     * @param string $sizeCm
     *
     * @return Product
     */
    public function setSizeCm($sizeCm)
    {
        $this->sizeCm = $sizeCm;

        return $this;
    }

    /**
     * Get sizeCm
     *
     * @return string
     */
    public function getSizeCm()
    {
        return $this->sizeCm;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Product
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set composition
     *
     * @param string $composition
     *
     * @return Product
     */
    public function setComposition($composition)
    {
        $this->composition = $composition;

        return $this;
    }

    /**
     * Get composition
     *
     * @return string
     */
    public function getComposition()
    {
        return $this->composition;
    }

    /**
     * Set form
     *
     * @param string $form
     *
     * @return Product
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set unitPrice
     *
     * @param string $unitPrice
     *
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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

    

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Product
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
     * Add user
     *
     * @param \ProductBundle\Entity\ProductLog $user
     *
     * @return Product
     */
    public function addUser(\ProductBundle\Entity\ProductLog $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ProductBundle\Entity\ProductLog $user
     */
    public function removeUser(\ProductBundle\Entity\ProductLog $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add imagesProduct
     *
     * @param \ProductBundle\Entity\ImageProduct $imagesProduct
     *
     * @return Product
     */
    public function addImagesProduct(\ProductBundle\Entity\ImageProduct $imagesProduct)
    {
        $this->imagesProduct[] = $imagesProduct;

        return $this;
    }

    /**
     * Remove imagesProduct
     *
     * @param \ProductBundle\Entity\ImageProduct $imagesProduct
     */
    public function removeImagesProduct(\ProductBundle\Entity\ImageProduct $imagesProduct)
    {
        $this->imagesProduct->removeElement($imagesProduct);
    }

    /**
     * Get imagesProduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImagesProduct()
    {
        return $this->imagesProduct;
    }

    /**
     * Add category
     *
     * @param \CategoryBundle\Entity\Category $category
     *
     * @return Product
     */
    public function addCategory(\CategoryBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \CategoryBundle\Entity\Category $category
     */
    public function removeCategory(\CategoryBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setCategory($category)
    {
        return $this->category=$category;
    }

    /**
     * Add package
     *
     * @param \PackageBundle\Entity\Package $package
     *
     * @return Product
     */
    public function addPackage(\PackageBundle\Entity\Package $package)
    {
        $this->package[] = $package;

        return $this;
    }

    /**
     * Remove package
     *
     * @param \PackageBundle\Entity\Package $package
     */
    public function removePackage(\PackageBundle\Entity\Package $package)
    {
        $this->package->removeElement($package);
    }

    /**
     * Get package
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set package
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setPackage($package)
    {
        return $this->package=$package;
    }

    /**
     * Add provider
     *
     * @param \ProviderBundle\Entity\Provider $provider
     *
     * @return Product
     */
    public function addProvider(\ProviderBundle\Entity\Provider $provider)
    {
        $this->provider[] = $provider;

        return $this;
    }

    /**
     * Remove provider
     *
     * @param \ProviderBundle\Entity\Provider $provider
     */
    public function removeProvider(\ProviderBundle\Entity\Provider $provider)
    {
        $this->provider->removeElement($provider);
    }

    /**
     * Get provider
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set provider
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setProvider($provider)
    {
        return $this->provider=$provider;
    }

}
