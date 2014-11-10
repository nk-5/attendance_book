<?php
App::uses('AppController', 'Controller');
$components = array('Auth', 'Session');

class AppointmentsController extends AppController {
  public $uses = array(
    'Appointment'
  );

  public function beforeFilter()
  {
    parent::beforeFilter();
    //認証不要ページの指定
    $this->Auth->allow('index');
    //$this->layout = 'mylayout';
  }

  public function index($date_id = 0)
  {
    //指定した日付データを取得
    if($date_id){
      $strdate = date('Y年m月d日', strtotime($date_id));
      $date = date('Y-m-d', strtotime($date_id));
      $link = date('Ymd', strtotime($date_id));
   }else{
      $strdate = date('Y年m月d日');
      $date = date('Y-m-d');
      $link = date('Ymd');
   }
    
    //予約データ取得
    $appo = $this->Appointment->find('all', array(
      'conditions' => array(
        'date' => $date
      )
    ));

    //ログイン状態チェック
    $user = $this->Auth->user();
    if(empty($user)){
      $this->set('str', 'Login');
      $this->set('page', 'login');
    }else{
      $this->set('str', 'MyPage');
      $this->set('page', 'view/'.$user['id']);
    }

    //データ渡し
    $this->set('appointments', $appo);
    $this->set('strdate', $strdate);
    $this->set('link', $link);
    

    $sqlevents = $this->Appointment->query("SELECT events.id AS id, events.name AS title, events.date AS start, events.order AS `order` FROM attendance_book.appointments as events;");

    $events = array();
    for($a=0; $a<count($sqlevents); $a++){

      $events[] = array(
        'id' => $sqlevents[$a]["events"]["id"],
        'title' => $sqlevents[$a]["events"]["title"] . "  ". $sqlevents[$a]["events"]["order"],
        'start' => $sqlevents[$a]["events"]["start"]
      );
    }

    $jsonevents = json_encode($events);
    $this->set('jsonevents',$jsonevents);
  }

  public function add($date_id = null)
  {
    //日付取得
    if($date_id){
      $strdate = date('Y年m月d日', strtotime($date_id));
      $date = date('Y-m-d', strtotime($date_id));
      $link = date('Ymd', strtotime($date_id));
    }else{
      $strdate = date('Y年m月d日');
      $date = date('Y-m-d');
      $link = date('Ymd');
    }
    if($this->request->is('post')){
      //ユーザー情報取得
      $user_id = $this->data['Appointment']['user_id'];
      $name = $this->data['Appointment']['name'];
      $username = $this->data['Appointment']['username'];

      $appointments = $this->Appointment->find('all', array(
        'conditions' => array(
          'date' => $this->request->data['Appointment']['date'],
          'username' => $this->request->data['Appointment']['username']
        )
      ));

      //重複がないか確認
      if($appointments){
        $this->Session->setFlash('重複しています', 'default', array('class' => 'flash_failure'));
      }elseif($this->request->data['Appointment']['date'] == null){
          $this->Session->setFlash('空欄を埋めてください','default',array('class' => 'flash_failure'));
      }
      else{
      //問題ないなら予約データをSQLに保存
      $data = array('appointments' => array('user_id' => $user_id),
                                      array('name' => $name),
                                      array('username' => $username),
                                      array('date' => $date));
      $this->Appointment->save($this->request->data);
      if($this->Appointment->save($this->request->data)){
        $this->Session->setFlash('保存されました','default', array('class' => 'flash_success'));
      }
      }
    }

    //データ渡し
    $user = $this->Auth->user();
    $this->set('user_id',$user['id']);
    $this->set('username', $user['username']);
    $this->set('name', $user['name']);
    $this->set('date', $date);
    $this->set('strdate', $date);
    $this->set('link', $link);
    $this->set('strdate', $strdate);
  }

  public function delete($id = null){
    if(!$this->request->is('post')){
      throw new MethodNotAllowedException();
    }
    $this->Appointment->id = $id;
    $user = $this->Auth->user();
    if(!$this->Appointment->exists()){
      throw new NotFoundException(__('Invalid appointment'));
    }
    if($this->Appointment->delete()){
      $this->Session->setFlash('削除されました','default',array('class' => 'flash_success'));
      $this->redirect(array('controller' => 'users',
      'action' => 'view/'.$user['id']));
    }
    $this->Session->setFlash('削除されました','default',array('class' => 'flash_success'));
  }


  public function delete_All($username){
    $conditions = array('username' => "$username");
    if(!$this->Appointment->deleteAll($conditions))
      $this->Session->setFlash('予約データも削除されました','default',array('class' => 'flash_success'));
  }

}
