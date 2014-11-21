<?php echo  $this->Html->css('appo'); ?>

<h2><?php echo __('名前の変更'); ?></h2>
<?php 
echo $this->Form->create('User');
echo $this->Form->input('name', array('label' => '新しい名前',  'value' => ''));
echo $this->Form->end('変更する'); ?>
