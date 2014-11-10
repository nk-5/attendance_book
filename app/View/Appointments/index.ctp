<?php echo  $this->Html->css('appo'); ?>
<div id='calendar'></div>


<script type="text/javascript">

$(document).ready(function(){
  $('#calendar').fullCalendar({
    titleFormat: {
      month: 'YYYY年M月',
    },
    columnFormat:{
      month: 'ddd'
    },
    dayNames: ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'],
    dayNamesShort: ['日','月','火','水','木','金','土'],


    editable: false,
    events: <?php echo $jsonevents; ?>,

    dayClick: function(){
      location.href="appointments/add";
    },
      eventClick: function(){
    }
  })
});
</script>

<div class="appointments index">
  <h2><?php echo __('今日の予定'); ?></h2>
  <h4><?php echo $strdate . __(' appointments'); ?></h4>


<table>
    <tr>
      <th>Name</th>
      <th>Order</th>
      <th>username</th>
    </tr>
  <tr>
  <?php foreach ($appointments as $appointment): ?>
      <td><?php echo $appointment['Appointment']['name']; ?></td>
      <td><?php echo $appointment['Appointment']['order']; ?></td>
      <td><?php echo $appointment['Appointment']['username']; ?></td>
    </tr>
  <?php endforeach ?>
  </table>

    
  </div>


<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('勤務予定の追加'), array('action' => 'add/'.$link)); ?></li>
    <li><?php echo $this->Html->link(__('マイページ'), array('controller' => 'users', 'action' => $page)); ?> </li>
    <li><?php echo $this->Html->link(__('ログアウト'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
  </ul>
</div>
</div>
