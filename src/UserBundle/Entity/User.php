<?php
// src/UserBundle/Entity/User.php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends BaseUser{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true )
     */
    private $deleted_at; 

    /**
     * @ORM\OneToMany(targetEntity="ProductBundle\Entity\ProductLog", mappedBy="user")
     */
    private $user_log;


    public function __construct()
    {
        parent::__construct();
        $this->created_at = new \DateTime(); 
    }

    /**
     * Get userLog
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserLog()
    {
        return $this->user_log;
    }

    /**
     * Set userLog
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setUserLog($user_log)
    {
        return $this->user_log=$user_log;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
     * Add userLog
     *
     * @param \ProductBundle\Entity\ProductLog $userLog
     *
     * @return User
     */
    public function addUserLog(\ProductBundle\Entity\ProductLog $userLog)
    {
        $this->user_log[] = $userLog;

        return $this;
    }

    /**
     * Remove userLog
     *
     * @param \ProductBundle\Entity\ProductLog $userLog
     */
    public function removeUserLog(\ProductBundle\Entity\ProductLog $userLog)
    {
        $this->user_log->removeElement($userLog);
    }
}
