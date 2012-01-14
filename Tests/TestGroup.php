<?php

namespace Venocl\GroupACLBundle\Tests;

class TestGroup
{
    private $id;
    
    public function __construct($id = 5)
    {
        $this->id = $id;
    }


    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return "group".$this->id;
    }            
}
