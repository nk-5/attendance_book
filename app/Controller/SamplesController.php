<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail','Network/Email');

class SamplesController extends AppController{

	public function index(){
		$email = new CakeEmail();

		$email->transport('Mail');

		$email->from('nkws27@gmail.com');
		$email->to('id0161akkokode@gmail.com');
		$email->subject('test mail');

		$messages = $email->send('test mail cakephp');

		$this->set('messages',$messages);
	}
}