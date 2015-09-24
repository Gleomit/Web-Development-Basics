<?php

namespace Core;

class User
{
    private $id;
    private $user;
    private $pass;
    private $food;
    private $gold;

    const GOLD_DEFAULT = 1500;
    const FOOD_DEFAULT = 1500;

    public function __construct($user, $pass, $id = null, $gold = null, $food = null) {
        $this->setId($id)
            ->setUsername($user)
            ->setPass($pass)
            ->setGold($gold)
            ->setFood($food);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return \Core\User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return \Core\User
     */
    public function setUsername($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * @param mixed $food
     * @return \Core\User
     */
    public function setFood($food)
    {
        $this->food = $food;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGold()
    {
        return $this->gold;
    }

    /**
     * @param mixed $gold
     * @return \Core\User
     */
    public function setGold($gold)
    {
        $this->gold = $gold;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     * @return \Core\User
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }
}