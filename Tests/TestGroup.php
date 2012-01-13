<?php

namespace Venocl\GroupACLBundle\Tests;

class TestGroup
{
    private $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }


    public function getId()
    {
        return $this->id;
    }
            
}
