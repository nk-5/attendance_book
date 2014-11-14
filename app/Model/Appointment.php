<?php

class Appointment extends AppModel
{
  var $name = 'Appointment';
  var $belongsTo = array(
    'User' => array(
      'className' => 'User',
      'conditions' => 'User.id = Appointment.user_id' ,
      'foreignKey' => ''),
    );
}
