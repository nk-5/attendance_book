<?php
App::uses('AppController', 'Controller');
$components = array('Auth', 'Session', 'Cookie','RequestHandler');

class AppointmentsController extends AppController {
  public $uses = array(
    'Appointment',
    'User',
    'Image'
  );


public $helpers = array('Html','Form','Session');

  public function beforeFilter()
  {
    parent::beforeFilter();
    //認証不要ページの指定
    $this->Auth->allow('index');
    $this->set('user', $this->Auth->user());
  }

  public function index($date_id = 0)
  {
    //日付データを取得
      $strdate = date('Y年m月d日');
      $date = date('Y-m-d');
      $now_days = date('t');

    //データ渡し
    $this->Session->setFlash('1 ~ 10日の予定が追加されている必要があります','default',array('class' => 'success'),'one');
    $this->Session->setFlash('11 ~ 20日の予定が追加されている必要があります','default',array('class' => 'success'),'two');
    $this->Session->setFlash('21 ~ '.$now_days.'の予定が追加されている必要があります','default',array('class' => 'success'),'three');

    
    //予約データ取得
    $appo = $this->Appointment->find('all', array(
      'conditions' => array(
        'date' => $date
      )
    ));

    //ログイン状態チェック
    $user = $this->Auth->user();

    //データ渡し
    $this->set('appointments', $appo);
    $this->set('strdate', $strdate);
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
      $strdate = date('Y年m月d日');
      $date = date('Y-m-d');
    if($this->request->is('post')){
      //ユーザー情報取得
      $user_id = $this->data['Appointment']['user_id'];
      $name = $this->Auth->user('name');
      $username = $this->Auth->user('username');

      $post_date = $this->request->data['Appointment']['date'];
      $appointments = $this->Appointment->query("SELECT users.id AS `user_id`, users.name AS `name`, users.username AS `username`, appointments.date AS `date` 
        FROM appointments, users
        WHERE appointments.date = \"$post_date\" AND appointments.user_id = \"$user_id\"");

      //問題がないか確認
      if($appointments){
        $this->Session->setFlash('重複しています', 'default', array('class' => 'flash_failure'));
      }elseif($this->request->data['Appointment']['date'] == null || $this->request->data['Appointment']['start'] == null || $this->request->data['Appointment']['start'] == null){
          $this->Session->setFlash('空欄を埋めてください','default',array('class' => 'flash_failure'));
      }elseif(strtotime(date('Y-m-d')) > strtotime($this->request->data['Appointment']['date'])){
        //過去への登録禁止
          $this->Session->setFlash('過去への登録はできません','default',array('class' => 'flash_failure'));
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
      $this->redirect(array('controller' => 'appointments',
      'action' => 'index'));
    }
    $this->Session->setFlash('削除されました','default',array('class' => 'flash_success'));
  }


  public function delete_All($username){
    $conditions = array('username' => "$username");
    if(!$this->Appointment->deleteAll($conditions))
      $this->Session->setFlash('予約データも削除されました','default',array('class' => 'flash_success'));
  }

  public function whitebord(){
    //日付取得
      $date = date('Y-m-d');
      $now_days = date('t');

      //ユーザー情報取得
      $login_user_id = $this->Auth->user('id');
      $login_user_param = $this->Appointment->query("SELECT * FROM appointments WHERE user_id = \"$login_user_id\" ORDER BY appointments.user_id,appointments.date asc");
      $login_user_param_count = $this->Appointment->query("SELECT COUNT(user_id) FROM appointments WHERE user_id = \"$login_user_id\"");
      $user_id_count = $this->User->query("SELECT COUNT(id) FROM users");
      $param_count = $this->Appointment->query("SELECT COUNT(id) FROM appointments");
      
      $login_user_image_params = $this->Image->query("SELECT * FROM images WHERE user_id = \"$login_user_id\"");
      $image_param_su = $this->Image->query("SELECT COUNT(user_id) FROM images");

      //データ渡し
    $this->Session->setFlash('1 ~ 10日の予定が追加されている必要があります','default',array('class' => 'success'),'one');
    $this->Session->setFlash('11 ~ 20日の予定が追加されている必要があります','default',array('class' => 'success'),'two');
    $this->Session->setFlash('21 ~ '.$now_days.'の予定が追加されている必要があります','default',array('class' => 'success'),'three');

    $this->set('login_user_ids',$login_user_id);
    $this->set('login_user_params',$login_user_param);
    $this->set('login_user_param_count',$login_user_param_count);

    $this->set('user_names', $this->User->find('all'));
    $this->set('user_id_counts', $user_id_count);
    $this->set('param_counts',$param_count); 
    $this->set('appointment_params', $this->Appointment->find('all',array('order' => array('user_id' => 'asc','date' => 'asc'))));
   
      //画像出力処理
    $this->set('image_params', $this->Image->find('all',array('order' => array('user_id' => 'asc'))));
    $this->set('login_user_image_params', $login_user_image_params);
    $this->set('image_param_su',$image_param_su);

    $this->set('title_for_layout', 'ホワイトボードページ');
  }

  public function wbsAppoInsert(){
      $this->autoRender = FALSE;
         if($this->request->is('ajax')) {
          if(intval(substr($this->data['start'],0,2)) < 12){
            $data = '○';
          }else{
            $data = '△';
          }
          if($this->Appointment->save($this->request->data)){
            }
          else{
             $this->Session->setFlash('failed!');
         }
        }
         return $data;
  }



  public function wbsAppoDelete(){
    $this->autoRender = FALSE;
    if($this->request->is('ajax')){
    $delete_id = $this->request->data('user_id');
    $delete_day = $this->request->data('date');

    if($this->Appointment->deleteAll(array('Appointment.user_id' => $delete_id)) && $this->Appointment->deleteAll(array('Appointment.date' => $delete_day))){
      
    }else{
           $this->Session->setFlash('Delete failed!');
      }
    }
  }

  public function whitebord_prev(){
     //日付取得
           $date = date('Y-m-d');
      //ユーザー情報取得
    
      $login_user_id = $this->Auth->user('id');
      $login_user_param = $this->Appointment->query("SELECT * FROM appointments WHERE user_id = \"$login_user_id\" ORDER BY appointments.user_id,appointments.date asc");
      $login_user_param_count = $this->Appointment->query("SELECT COUNT(user_id) FROM appointments WHERE user_id = \"$login_user_id\"");
      $user_id_count = $this->User->query("SELECT COUNT(id) FROM users");
      $param_count = $this->Appointment->query("SELECT COUNT(id) FROM appointments");

      //データ渡し
    // $user = $this->Auth->user();
    $this->set('login_user_ids',$login_user_id);
    $this->set('login_user_params',$login_user_param);
    $this->set('login_user_param_count',$login_user_param_count);


    $this->set('user_names', $this->User->find('all'));
    $this->set('user_id_counts', $user_id_count);
    $this->set('param_counts',$param_count);
    $this->set('appointment_params', $this->Appointment->find('all',array('order' => array('user_id' => 'asc','date' => 'asc'))));
    $this->set('title_for_layout', 'WBS前月');

  }

    public function whitebord_next(){
     //日付取得
      $date = date('Y-m-d');
      //ユーザー情報取得
      $login_user_id = $this->Auth->user('id');
      $login_user_param = $this->Appointment->query("SELECT * FROM appointments WHERE user_id = \"$login_user_id\" ORDER BY appointments.user_id,appointments.date asc");
      $login_user_param_count = $this->Appointment->query("SELECT COUNT(user_id) FROM appointments WHERE user_id = \"$login_user_id\"");
      $user_id_count = $this->User->query("SELECT COUNT(id) FROM users");
      $param_count = $this->Appointment->query("SELECT COUNT(id) FROM appointments");

      //データ渡し

    $this->set('login_user_ids',$login_user_id);
    $this->set('login_user_params',$login_user_param);
    $this->set('login_user_param_count',$login_user_param_count);


    $this->set('user_names', $this->User->find('all'));
    $this->set('user_id_counts', $user_id_count);
    $this->set('param_counts',$param_count);
    $this->set('appointment_params', $this->Appointment->find('all',array('order' => array('user_id' => 'asc','date' => 'asc'))));
    $this->set('title_for_layout', 'WBS翌月');

  }


    function contents($filename) {
        $this->layout = false;
        $image = $this->Image->findByFilename($filename);
        if (empty($image)) {
            $this->cakeError('error404');
        }
        header('Content-type: image/jpeg');//$image['Image']['filetype']
        echo $image['Image']['contents'];
    }


}