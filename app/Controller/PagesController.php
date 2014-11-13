<?php
class PagesController extends AppController{
  
  public function beforeFilter()
  {
    parent::beforeFilter();
    //認証不要ページの指定
    $this->Auth->allow('display');
    //$this->layout = 'mylayout';
  }

  public function display(){
  $this->redirect(array(
    'controller' => 'appointments'
  ));
  }
}
