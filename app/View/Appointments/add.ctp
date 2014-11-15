<?php echo  $this->Html->css('appo'); ?>

<script type="text/javascript">
     $(function(){
       $('#date').datepicker({
         dateFormat: 'yy-mm-dd',
       });
     });
</script>

<div class="appointments form">
	<h2><?php echo __('出勤予定の追加'); ?></h2>
	<?php
		echo $this->Form->create('Appointment');
    echo '<h3>' . $name . '</h3>';
?>

<?php
		echo $this->Form->hidden('user_id', array(
				'value' => $user_id
      ));
   echo $this->Form->hidden('username', array(
      'value' => $username
    ));
    echo $this->Form->hidden('name', array(
      'value' => $name
    ));


    echo $this->Form->input('order', array(
      'type' => 'radio',
      'value' => 'AM~',
      'options' => array('AM~' => 'AM~',
                        'PM~' => 'PM~')
                      ));

    echo $this->Form->input('date', array(
      'type' => 'text',
      'id' => 'date',
    ));
?>
<div class="submit-button"
<?php
		echo $this->Form->end(__('Submit'));
?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
    <li><?php echo $this->Html->link(__('HOME'), array('controller' => 'appointments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('マイページ'), array('controller' => 'users', 'action' => 'view/'.$user_id)); ?> </li>
	</ul>
</div>

</body>
