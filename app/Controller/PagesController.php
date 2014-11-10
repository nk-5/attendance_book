<?php
class PagesController extends AppController{
  public function display(){
  $this->redirect(array(
    'controller' => 'appointments'
  ));
  }
}
