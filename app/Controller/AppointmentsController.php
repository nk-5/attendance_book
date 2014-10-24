<?php
App::uses('AppController', 'Controller');
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
      'conditions' => array('date' => $date),
      'order' => array('start' => 'ASC')//並び順の指定,
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
    //タイムライン追加用クラス名生成
    for($i=0; $i<count($appo); $i++){
      $appo[$i]['Appointment']['class'] = 'appo' . substr($appo[$i]['Appointment']['start'], 0, 2);
      $appo[$i]['Appointment']['height'] = $appo[$i]['Appointment']['order_id'];
      //自分の部分に色を付けるためのクラス名 meを生成
      if($appo[$i]['Appointment']['user_id'] == $user['id']){
        $appo[$i]['Appointment']['class'] .= ' me';
        $appo[$i]['Appointment']['name'] = $appo[$i]['User']['name'];
      }else{
        //（変更）Already => 予約者名のまま

      }
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
    //時間帯取得
    $times = $this->Time->find('list',array(
      'fields' => 'time'
    ));
    $i = 1;
    foreach($times as $time){
      $times[$i] = substr($time, 0, 5);
      $i++;
    }
    //オーダー取得
    $orders = $this->Order->find('list', array(
      'fields' => 'order'
    ));
    if($this->request->is('post')){
      $data['Appointment']['user_id'] = $this->request->data['Appointment']['user_id'];
      $data['Appointment']['order_id'] = $this->request->data['Appointment']['order'];
      $data['Appointment']['date'] = $this->request->data['Appointment']['date'];
      $data['Appointment']['start'] = $this->request->data['Appointment']['time'];
      $data['Appointment']['table'] = 1; //1はフラグ
    }
    //予約済みデータ取得
    $appo = $this->Appointment->find('all', array(
      'conditions' => array(
        'date' => $data['Appointment']['date']
      ),
      'order' => array('start' => 'ASC')
    ));

    //席が被った場合の自動変更処理　必要なし(?)
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
