<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

$components = array('Auth', 'Session');
class UsersController extends AppController
{
  public $uses = array(
    'Appointment',
    'User',
    'Image'
  );
  
  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow('login','add');
    $this->set('user', $this->Auth->user());
  }

  public function login() 
  {
    $this->set('title_for_layout', 'ログイン');
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

  // public function pass_create()
  // {

  //   $email_adress = $this->request->data['User']['email_adress'];
  //   $new_pass = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'),0,8);
    

  //    $Email = new CakeEmail('smtp');

  //   $Email->from ='nkws27@gmail.com';
  //   $Email->to = $email_adress;
  //   $Email->subject = 'パスワード変更';
  //   $Email->send = $new_pass;


  // }

  public function logout()
  {
    $this->Auth->logout();
    $this->redirect(array('controller' => 'users',
    'action' => 'login'));
  }

  public function view($id = null)
  {
    $this->set('title_for_layout', 'マイページ');
    //ユーザー情報取得
    //idをurlから取得
    $this->User->id = $id;
    $login_user_image_params = $this->Image->query("SELECT * FROM images WHERE user_id = \"$id\"");
    $image_param_su = $this->Image->query("SELECT COUNT(user_id) FROM images");
    
    $this->set('login_user_image_params', $login_user_image_params);
    $this->set('image_param_su',$image_param_su);
    $tmp_user = $this->User->find('all',array(
      'conditions' => array(
        'id' => $id
      )
    ));

    //今日の日付を取得
      $strdate = date('Y年m月d日');
      $date = date('Y-m-d');

    //例外処理  
    if(!$this->User->exists()){
      throw new NotFoundException(__('Invalid user'));
    }
    //予約データ取得
    $appo = $this->Appointment->find('all', array(
      'conditions' => array('user_id' => $tmp_user[0]["User"]["id"],
                            'date >=' => $date,
                            ),
      'order' => 'date',
    ));
    //データ渡し
    $this->set('appointments', $appo);
    $this->set('user', $this->Auth->user());
    $this->set('tmp_user',$tmp_user);
  }

  public function add()
  {
    $this->set('title_for_layout', '新規登録');
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
        $this->Session->setFlash(__('空欄を埋めてください。'));
      }elseif($i == 1){
        $this->Session->setFlash('このユーザー名は使われています。','default',array('class' => 'flash_failure'));
      }
      elseif($this->data['User']['password'] !== $this->data['User']['確認用']){
        $this->Session->setFlash('パスワードが一致しません。','default',array('class' => 'flash_failure'));
      }
      else{
      $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
      $this->User->create();

      if($this->User->save($this->request->data)){
        $this->Session->setFlash('保存されました。','default', array('class' => 'flraush_success'));
        $this->redirect(array('action' => 'login'));
      }else{
        $this->Session->setFlash('保存できませんでした。','default',array('class' => 'flash_failure'));
      }
    }

   }
  }

  public function admin($id)
  {
    $this->set('title_for_layout', 'ユーザーの管理');
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

  public function delete($id = null, $username, $own_id)
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
      'action' => 'admin/'.$own_id));
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

  public function unadministrate($id, $own_id){    if(!$this->request->is('post')){
      throw new MethodNotAllowedException();
    }

    $data = array('admin' => 0);
    $conditions = array('id' => "$id");

    $this->User->updateAll($data, $conditions);
    $this->Session->setFlash('管理者権限が変更されました','default',array('class' => 'flash_success'));
    $this->redirect(array('controller' => 'users',
    'action' => 'admin/'.$own_id));
  }

  public function pass($id = null){
    $this->set('title_for_layout', 'パスワードの変更');
    $id = $this->Auth->user('id');
    $this->User->id = $id;
    if(!$this->User->exists()){
      throw new NotFoundException(__('無効なユーザーです'));
    }

    if($this->request->is('post')){
      $user = $this->User->read();
      if($user['User']['password'] === AuthComponent::password($this->request->data['User']['password'])){
        if($this->request->data['User']['new_password_1'] === $this->request->data['User']['new_password_2']){
          $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['new_password_1']);
          if($this->User->save($this->request->data)){
            $this->Session->setFlash('パスワードを変更しました', 'default', array('class' => 'flash_success'));
            $this->redirect(array('controller' => 'appointments', 'action' => 'index'));
          }else{
            $this->Session->setFlash('エラー', 'default', array('class' => 'flash_failure'));
          }
        }else $this->Session->setFlash('新しいパスワードが一致しません。もう一度入力してください。', 'default', array('class' => 'flash_failure'));
      }else $this->Session->setFlash('現在のパスワードが違います。もう一度入力してください。', 'default', array('class' => 'flash_failure'));
    }
    $this->render('pass');
  }

  public function name($id = null){
    $this->set('title_for_layout', '名前の変更');

    $id = $this->Auth->user('id');
    $this->User->id = $id;
    if(!$this->User->exists()){
      throw new NotFoundException(__('無効なユーザーです'));
    }

    if($this->request->is('post')){
      $user = $this->User->read();
      if($this->User->save($this->request->data)){

        $this->Session->setFlash('名前を変更しました', 'default', array('class' => 'flash_success'));

      $this->redirect(array('controller' => 'appointments','action' => '' ));
      }
    }
  }

   function contents($filename) {
        $this->layout = false;
        $image = $this->Image->findByFilename($filename);
        if (empty($image)) {
            $this->cakeError('error404');
        }
        // header('Content-type: image/jpeg');//$image['Image']['filetype']
        // echo 'h';
        // $this->response->header('Content-type', 'image/jpeg');
        // $this->response->header(array('Content-type' => 'image/jpeg'));
        $this->response->type('jpg');
        // $this->response->header('aaa', 'bbb');
        echo $image['Image']['contents'];
    }
}
