<?php
App::uses('AppController', 'Controller');
$components = array('Auth', 'Session');
class AppointmentsController extends AppController {
  public $uses = array(
    'Appointment',
    'Time',
    'Order'
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
    $this->set('prev', date('Ymd', strtotime($date . '-1 day')));
    $this->set('next', date('Ymd', strtotime($date . '+1 day')));
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
    //オーダー取得
    $orders = $this->Order->find('list', array(
      'fields' => 'order'
    ));
    if($this->request->is('post')){
/*
      $data['Appointment']['user_id'] = $this->request->data['Appointment']['user_id'];
      $data['Appointment']['order_id'] = $this->request->data['Appointment']['order'];
      $data['Appointment']['date'] = $this->request->data['Appointment']['date'];
      $data['Appointment']['start'] = $times[$this->request->data['Appointment']['time']];
      $data['Appointment']['table'] = 1; //1はフラグ
 */
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
        $this->Session->setFlash('Duplicated!');
      }else{
      //重複がないなら予約データをSQLに保存
      $data = array('appointments' => array('user_id' => $user_id),
                                      array('name' => $name),
                                      array('username' => $username),
                                      array('date' => $date),
                                      array('order' => $orders));
      $this->Appointment->save($this->request->data);
      }
    }
    //予約済みデータ取得
    /*
    $appo = $this->Appointment->find('all', array(
      'conditions' => array(
        'date' => $data['Appointment']['date']
      ),
      'order' => array('start' => 'ASC')
    ));
     */

    //データ渡し
    $user = $this->Auth->user();
    $this->set('user_id',$user['id']);
    $this->set('username', $user['username']);
    $this->set('name', $user['name']);
    $this->set('date', $date);
    $this->set('strdate', $date);
    $this->set('orders', $orders);
    $this->set('link', $link);
    //$this->set('appointments', $appo);
    $this->set('strdate', $strdate);
    $this->set('link', $link);
    $this->set('prev', date('Ymd', strtotime($date . '-1 day')));
    $this->set('next', date('Ymd', strtotime($date . '+1 day')));
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
      $this->Session->setFlash(__('Appointment deleted'));
      $this->redirect(array('controller' => 'users',
      'action' => 'view/'.$user['id']));
    }
    $this->Session->setFlash(__('Appointment was not deleted'));
  }
}
