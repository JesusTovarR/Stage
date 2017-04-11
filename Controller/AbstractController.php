<?php

namespace Controller;

abstract class AbstractController
{
    
    protected $token = null;

    public function __construct($token) {
        $this->token = $token;
    }

}
