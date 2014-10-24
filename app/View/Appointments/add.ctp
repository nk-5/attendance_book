<?php echo  $this->Html->css('appo'); ?>

<body background="room.jpg">

<div class="appointments form">
	<h2><?php echo __('Add Appointment'); ?></h2>
	<?php
		echo $this->Form->create('Appointment');
    echo '<h3>' . $user_name . '</h3>';
    var_dump($user_name);
    var_dump($user_id);
    var_dump($date);
    var_dump($strdate);
    var_dump($times);
    var_dump($orders);
		echo $this->Form->hidden('user_id', array(
				'value' => $user_id
		));
		echo $this->Form->hidden('date', array(
				'value' => $date
		));
		echo '<h4>' . $strdate . __(' appointments') .'</h4>';
		echo $this->Form->input('time', array(
				'type' => 'select',
				'options' => $times,
				'after' => ' 〜'
		));
		echo $this->Form->input('order', array(
				'type' => 'radio',
				'options' => $orders,
				'value' => 1
		));
		echo $this->Form->end(__('Submit'));
	?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('MyPage'), array('controller' => 'users', 'action' => 'view/'.$user_id)); ?> </li>
		<li><?php echo $this->Html->link(__('List Appointments'), array('action' => 'index/'.$link)); ?></li>
	</ul>
</div>

</body>
