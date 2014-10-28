<?php

    error_reporting(E_ALL);

    new controller;

    class controller {

            public $uri;

            function __construct() {
                $this->uri = 'Hello';
                $class = 'uclass';
                $function = 'ufunction';
                // call class::method here by variable name
                uclass::ufunction(); //works fine but how to call by variable $class::$function
                
                $class = new uclass();
                $class::$function();
            }

    }

    class uclass extends controller {

            public $work;

            function ufunction() {
                print_r($this);
            }

    }
    
    
    $asd = new uclass();