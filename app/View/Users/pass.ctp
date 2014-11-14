<?php echo  $this->Html->css('appo'); ?>

<h2><?php echo __('パスワードの変更'); ?></h2>
<?php 
echo $this->Form->create('User');
echo $this->Form->input('password', array('label' => '現在のパスワード', 'value' => ''));
echo $this->Form->input('new_password_1', array('type' => 'password', 'label' => '新しいパスワード', 'value' => ''));
echo $this->Form->input('new_password_2', array('type' => 'password', 'label' => '新しいパスワード（確認用）', 'value' => ''));
echo $this->Form->end('変更する'); ?>
