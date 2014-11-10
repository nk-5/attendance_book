<?php
App::uses('AppController', 'Controller');
App::uses('AppointmentsController','Controller');
$components = array('Auth', 'Session');
class UsersController extends AppController
{
  public $uses = array(
    'Appointment',
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
        $this->redirect($this->Auth->redirect(array('controller' => 'appointments')));
      }else{
        //ログイン失敗の場合
        $this->Session->setFlash('ログイン情報が間違っています', 'default', array('class' => 'flash_failure'), 'auth');
        $this->set('email', $this->request->data['User']['username']);
      }
    }else{
      $this->set('email', '');
    }
  }

  public function logout()
  {
    $this->Auth->logout();
    $this->redirect(array('controller' => 'users',
    'action' => 'login'));
  }

  public function view($id = null)
  {
    //ユーザー情報取得
    $this->User->id = $id;
    //今日の日付を取得
      $strdate = date('Y年m月d日');
      $date = date('Y-m-d');
      $link = date('Ymd');

    //例外処理  
    if(!$this->User->exists()){
      throw new NotFoundException(__('Invalid user'));
    }
    $user = $this->Auth->user();
    //予約データ取得
    $appo = $this->Appointment->find('all', array(
      'conditions' => array('user_id' => $user['id'],
                            'date >=' => $date,
                            ),
      'order' => 'date',
    ));
    //データ渡し
    $this->set('user', $this->User->read(null, $id));
    $this->set('appointments', $appo);
    //$this->set('orders', $orders);
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
        $this->Session->setFlash(__('空欄を埋めてください'));
      }elseif($i == 1){
        $this->Session->setFlash('このユーザー名は使われています','default',array('class' => 'flash_failure'));
      }
      else{
      $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
      $this->User->create();

      if($this->User->save($this->request->data)){
        $this->Session->setFlash('保存されました','default', array('class' => 'flraush_success'));
        $this->redirect(array('action' => 'login'));
      }else{
        $this->Session->setFlash('保存できませんでした','default',array('class' => 'flash_failure'));
      }
    }

   }
  }

  public function admin($id)
  {
    $this->User->id = $id;
    
    //ユーザー情報取得
    $all_users = $this->User->find('all');
    $users = $this->User->find('all', array(
      'conditions' => array('id' => $id)));
    if($users[0]['User']['admin'] == 0){
      $this->Session->setFlash('管理者権限が必要です','default',array('class' => 'flash_failure'));
      $this->redirect(array('controller' => 'users','action' => "view/".$users[0]['User']['id']));
    }

    $user = $this->Auth->user();
    
    //データ渡し
    $this->set('users',$all_users);
    $this->set('own_id', $id);
    //$this->set('orders', $orders);
  }

  public function delete($id = null, $username)
  {

    if(!$this->request->is('post')){
      throw new MethodNotAllowedException();
    }
    $user = $this->Auth->user();

    //削除するユーザーの予約を全削除
    $users = $this->User->find('all');

    $this->requestAction("Appointments/delete_All/$username");

    if($this->User->delete($id)){
      $this->Session->setFlash('削除されました','default',array('class' => 'flash_success'));
      $this->redirect(array('controller' => 'users',
      'action' => 'admin'));
    }
    $this->Session->setFlash('削除されました','default',array('class' => 'flash_success'));
  }

  public function administrate($id, $own_id){
    if(!$this->request->is('post')){
      throw new MethodNotAllowedException();
    }

    $data = array('admin' => 1);
    $conditions = array('id' => "$id");

    $this->User->updateAll($data, $conditions);
    $this->Session->setFlash('管理者権限が変更されました','default',array('class' => 'flash_success'));
    $this->redirect(array('controller' => 'users',
    'action' => "admin/".$own_id));
  }

  public function unadministrate($id, $own_id){
    if(!$this->request->is('post')){
      throw new MethodNotAllowedException();
    }

    $data = array('admin' => 0);
    $conditions = array('id' => "$id");

    $this->User->updateAll($data, $conditions);
    $this->Session->setFlash('管理者権限が変更されました','default',array('class' => 'flash_success'));
    $this->redirect(array('controller' => 'users',
    'action' => 'admin/'.$own_id));
  }
}
