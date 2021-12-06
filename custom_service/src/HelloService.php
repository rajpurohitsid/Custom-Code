<?php

/**
* @file providing the service that say hello world and hello 'given name'.
*
*/

namespace  Drupal\custom_service;

class HelloService {

 protected $say_something;

 public function __construct() {
   $this->say_something = 'Hello World!';
 }

 public function  sayHello($name = ''){
   if (empty($name)) {
     return $this->say_something;
   }
   else {
     return "Hello " . $name . "!";
   }
 }

}

//Access Service using This
// $service = \Drupal::service('custom_service.say_hello');
// dsm($servic);
