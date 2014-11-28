<?php echo  $this->Html->css('appo'); ?>
<?php    echo $this->Html->css('clockpicker/assets/css/bootstrap.min.css');?>

<h2><?php echo __('名前の変更'); ?></h2>
<?php 
echo $this->Form->create('User');
echo $this->Form->input('name', array('label' => '新しい名前',  'value' => ''));
echo $this->Form->end('変更する'); ?>
