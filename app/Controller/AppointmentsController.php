<?php
App::uses('AppController', 'Controller');
$components = array('Auth', 'Session', 'Cookie');

class AppointmentsController extends AppController {
  public $uses = array(
    'Appointment',
    'User'
  );

  public function beforeFilter()
  {
    parent::beforeFilter();
    //認証不要ページの指定
    $this->Auth->allow('index');
    $this->set('user', $this->Auth->user());
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
    $this->set('user_id', $user['id']);
    $this->set('name',$user['name']);
    $this->set('title_for_layout', 'HOME');

    $sqlevents = $this->Appointment->query("SELECT appointments.id AS `id`, users.name AS `title`, appointments.date AS `date`, appointments.start AS `start`, appointments.end AS `end`, users.id AS className FROM appointments, users WHERE appointments.user_id = users.id");

    $events = array();
    for($a=0; $a<count($sqlevents); $a++){

      $events[] = array(
        'id' => $sqlevents[$a]["appointments"]["id"],
        'title' => $sqlevents[$a]["users"]["title"],
        'start' => $sqlevents[$a]["appointments"]["date"] ."T". $sqlevents[$a]["appointments"]["start"],
        'end' => $sqlevents[$a]["appointments"]["date"] ."T". $sqlevents[$a]["appointments"]["end"],
        'className' => "class" . $sqlevents[$a]["users"]["className"]
      );
    }

    $jsonevents = json_encode($events);
    $this->set('jsonevents',$jsonevents);
  }

  public function add($date_id = null)
  {
    if(isset($_GET['year'])){
      $click_year = $_GET['year'];
      $this->set('year',$click_year);
    }
    if(isset($_GET['month'])){
      $click_month = $_GET['month'];
      $this->set('month',$click_month);
    }
    if(isset($_GET['day'])){
      $click_day = $_GET['day'];
      $this->set('day',$click_day);
    }
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
      $name = $this->Auth->user('name');
      $username = $this->Auth->user('username');

      $post_date = $this->request->data['Appointment']['date'];
      $appointments = $this->Appointment->query("SELECT users.id AS `user_id`, users.name AS `name`, users.username AS `username`, appointments.date AS `date` 
        FROM appointments, users
        WHERE appointments.date = \"$post_date\" AND appointments.user_id = \"$user_id\"");

      //重複がないか確認
      if($appointments){
        $this->Session->setFlash('重複しています', 'default', array('class' => 'flash_failure'));
      }elseif($this->request->data['Appointment']['date'] == null || $this->request->data['Appointment']['start'] == null || $this->request->data['Appointment']['start'] == null){
          $this->Session->setFlash('空欄を埋めてください','default',array('class' => 'flash_failure'));
      }elseif(strtotime($this->request->data['Appointment']['start']) > strtotime($this->request->data['Appointment']['end'])){
        //start時間がend時間より未来の場合
        $this->Session->setFlash('開始時刻と終了時刻の順序が正しくありません。確認してください。','default',array('class' => 'flash_failure'));
      }
      else{
      //問題ないなら予約データをSQLに保存
      $data = array('appointments' => array('user_id' => $user_id),
        array('date' => $date));
      //Cookie保存
      $this->Cookie->write('start', $this->request->data["Appointment"]["start"]);
      $this->Cookie->write('end', $this->request->data["Appointment"]["end"]);
      $this->Appointment->save($this->request->data);
      if($this->Appointment->save($this->request->data)){
      $this->Session->setFlash('保存しました', 'default', array('class' => 'flash_success'));
      $this->redirect(array('controller' => 'appointments', 'action' => 'index'));
      }
      }
    }

    //データ渡し
    $user = $this->Auth->user();
    $this->set('user_id',$user['id']);
    $this->set('username', $this->Auth->user('username'));
    $this->set('name', $user['name']);
    $this->set('date', $date);
    $this->set('strdate', $date);
    $this->set('link', $link);
    $this->set('strdate', $strdate);
    $this->set('title_for_layout', '予定追加');
    $this->set('cookie_start', $this->Cookie->read('start'));
    $this->set('cookie_end', $this->Cookie->read('end'));
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
