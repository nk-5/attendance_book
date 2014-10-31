
<div class="appointments index">
  <h2><?php echo __('Appointments'); ?></h2>
  <div class="paging">
  <?php
    echo $this->Html->link('< '.__('prev day'), array('action' => 'index/'.$prev));
    echo $this->Html->link(__('next day').' >', array('action' => 'index/'.$next));
  ?>
  </div>
  <h4><?php echo $strdate . __(' appointments'); ?></h4>

<?php
    //print_r($appointments);
?>
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

</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__($str), array('controller' => 'users', 'action' => $page)); ?> </li>
    <li><?php echo $this->Html->link(__('Add Appointment'), array('action' => 'add/'.$link)); ?></li>
    <li><?php echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
  </ul>
</div>
