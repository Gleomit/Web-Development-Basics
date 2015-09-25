<?php

namespace SoftUni\ViewModels;

class UserInformation
{
    private $id;
    private $user;
    private $food;
    private $gold;

    public function __construct($user, $id = null, $gold = null, $food = null) {
        $this->setId($id)
            ->setUsername($user)
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
    private function setId($id)
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
    private function setUsername($user)
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
    private function setFood($food)
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
    private function setGold($gold)
    {
        $this->gold = $gold;
        return $this;
    }
}