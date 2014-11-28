<?php echo  $this->Html->css('appo'); ?>
<?php    echo $this->Html->css('clockpicker/assets/css/bootstrap.min.css');?>

<div class="users form">
	<h2><?php echo __('ログイン'); ?></h2>
	<?php
		echo $this->Session->flash('auth');
		echo $this->Form->create('User');
		echo $this->Form->input('username', array(
			'label' => __('E-mail', true),
			'value' => $email
		));
		echo $this->Form->input('password', array(
			'label' => __('Password', true),
			'value' => ''
		));
	?>
<?php echo $this->Form->end(__('Login')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('ユーザーの追加'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('HOME'), array('controller' => 'appointments', 'action' => 'index')); ?> </li>
	</ul>
</div>
