<?php
App::uses('AppController', 'Controller');
$components = array('Auth', 'Session');
class UsersController extends AppController
{
  public $uses = array(
    'Appointment',
    'Order',
    'User'
  );

  
  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow('login','add');
  }

  public function login() 
  {
    if($this->request->is('post')){
      //ログイン成功の場合
      if($this->Auth->login()){
        $this->redirect($this->Auth->redirect(array('controller' => 'appointments',
        'action => index')));
      }else{
        //ログイン失敗の場合
        $this->Session->setFlash(__('Mail or Password is different.'), 'default', 'array()', 'auth');
        $this->set('email', $this->request->data['User']['username']);
      }
    }else{
      $this->set('email', '');
    }
  }

  public function logout()
  {
    $this->Auth->logout();
    $this->redirect(array('controller' => 'appointments',
    'action' => 'index'));
  }

  public function view($id = null)
  {
    //ユーザー情報取得
    $this->User->id = $id;
    if(!$this->User->exists()){
      throw new NotFoundException(__('Invalid user'));
    }
    $user = $this->Auth->user();
    //予約データ取得
    $appo = $this->Appointment->find('all', array(
      'conditions' => array('user_id' => $user['id']),
      //'order' => array('start' => 'ASC')
    ));
    //オーダー取得
    $orders = $this->Order->find('list', array(
      'fields' => 'order'
    ));
    //データ渡し
    $this->set('user', $this->User->read(null, $id));
    $this->set('appointments', $appo);
    $this->set('orders', $orders);
  }

  public function add()
  {
      //ユーザー情報取得
    $users = $this->User->find('all');


    
    if($this->request->is('post')){

      //ユーザー重複確認のためのフラグ生成
      $i = 0;
      foreach($users as $user){
        if($user['User']['username'] === $this->request->data['User']['username'])
          $i++;
      }

      //例外処理
      if($this->request->data['User']['username'] == null || $this->request->data['User']['password'] == null || $this->request->data['User']['name'] == null){
        $this->Session->setFlash(__('Please fill in the blanks.'));
      }elseif($i == 1){
        $this->Session->setFlash(__('This username username already exist'));
      }
      else{
      $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
      $this->User->create();

      if($this->User->save($this->request->data)){
        $this->Session->setFlash(__('The user has been saved'));
        $this->redirect(array('action' => 'login'));
      }else{
        $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
      }
    }

   }
  }

  public function delete($id = null)
  {
    if(!$this->request->is('post')){
      throw new MethodNotAllowedException();
    }
    $this->User->id = $id;
    if(!$this->User->exists()){
      throw new NotFoundException(__('Invalid user'));
    }
    if($this->User->delete()){
      $this->Session->setFlash(__('User deleted'));
      $this->redirect(array('ation' => 'login'));
    }
    $this->Session->setFlash(__('User was not deleted'));
    $this->redirect(array('action' => 'view'));
  }
}
