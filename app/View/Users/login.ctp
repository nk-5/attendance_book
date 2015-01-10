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

	<form method="post" action="/users/pass_create">
		<div class="form-group" style="float:right;">
			<input type="hidden" name="email_adress" value="<?php echo $email; ?>">
			<button type="button" id="change_pass" class="btn btn-primary">パスワード変更</button>
		</div>
	</form>

	
	
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('ユーザーの追加'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('HOME'), array('controller' => 'appointments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('パスワード再設定'), array('controller' => 'users', 'action' => 'pass_create')); ?> </li>

	</ul>
</div>
