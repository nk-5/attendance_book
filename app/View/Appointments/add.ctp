<?php echo  $this->Html->css('appo'); 

    echo $this->Html->css('clockpicker/assets/css/bootstrap.min.css');
    echo $this->Html->css('clockpicker/dist/bootstrap-clockpicker.min.css');
    echo $this->Html->script('clockpicker/assets/js/bootstrap.min.js');
    echo $this->Html->script('clockpicker/dist/bootstrap-clockpicker.min.js');
?>
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
?>
<tr>
  <td>
<?php
    echo $this->Form->input('start',array(
      'type' => 'text',
      'class' => 'clockpicker',
      'data-autoclose' => 'true',
      'value' => $cookie_start,
    ));
?></td>
  <td>
<?php
    echo $this->Form->input('end',array(
      'type' => 'text',
      'class' => 'clockpicker',
      'data-autoclose' => 'true',
      'value' => $cookie_end,
    ));
?></td>
</tr>

<script type="text/javascript">
    $('.clockpicker').clockpicker();
</script>

<?php
    if(isset($year) && isset($month) && isset($day)){
    echo $this->Form->input('date', array(
      'type' => 'text',
      'id' => 'date',
      'value' => $year . "-" . $month . "-" . $day,
    ));}
    else{
    echo $this->Form->input('date', array(
      'type' => 'text',
      'id' => 'date',
    ));
    }
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
