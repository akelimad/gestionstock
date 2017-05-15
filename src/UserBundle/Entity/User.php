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
     * @ORM\OneToMany(targetEntity="ProductBundle\Entity\ProductLog", mappedBy="user")
     */
    private $user_log;


    public function __construct()
    {
        parent::__construct();
        //$this->roles=array();
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

}
