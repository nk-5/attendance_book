<?php echo $this->Html->css('appo') ?>

<div class="appointments index">
  <h2><?php echo __('Appointments'); ?></h2>
  <div class="paging">
  <?php
    echo $this->Html->link('< '.__('prev day'), array('action' => 'index/'.$prev));
    echo $this->Html->link(__('next day').' >', array('action' => 'index/'.$next));
  ?>
  </div>
  <h4><?php echo $strdate . __(' appointments'); ?></h4>
  <ul class="seatmap">
    <li class="seat1">(1)</li>
    <li class="seat2">(2)</li>
    <li class="seat3">(3)</li>
    <li class="seat4">(4)</li>
    <li class="seat5">(5)</li>
    <li class="seat6">(6)</li>
    <li class="seat7">(7)</li>
    <li class="seat8">(8)</li>
    <li class="seat9">(9)</li>
    <li class="seat10">(10)</li>
    <li class="seat11">(11)</li>
    <li class="seat12">(12)</li>
    <li class="seat13">(13)</li>
    <li class="seat14">(14)</li>
  </ul>

  <p class="dragarea-p">時間帯</p>
  <div class="dragarea">
    <div class="order1"><p>AM~</p></div>
    <div class="order2"><p>PM~</p></div>
  </div>
    



<!--
  <p class="appo-area-p1">1番席</p>
  <div class="appo-area1">
  <?php foreach ($appointments as $appointment): ?>
    <?php if ($appointment['Appointment']['table'] == 1): ?>
      <div class="<?php echo $appointment['Appointment']['class']; ?>" style="height:<?php echo $appointment['Appointment']['height']; ?>px">
        <p><?php echo $appointment['Appointment']['name'] ?></p>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
  </div>
  <p class="appo-area-p2">2番席</p>
  <div class="appo-area2">
  <?php foreach ($appointments as $appointment): ?>
    <?php if ($appointment['Appointment']['table'] == 2): ?>
      <div class="<?php echo $appointment['Appointment']['class']; ?>" style="height:<?php echo $appointment['Appointment']['height']; ?>px">
        <p><?php echo $appointment['Appointment']['name'] ?></p>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
  </div>
  <p class="appo-area-p3">3番席</p>
  <div class="appo-area3">
  <?php foreach ($appointments as $appointment): ?>
    <?php if ($appointment['Appointment']['table'] == 3): ?>
      <div class="<?php echo $appointment['Appointment']['class']; ?>" style="height:<?php echo $appointment['Appointment']['height']; ?>px">
        <p><?php echo $appointment['Appointment']['name'] ?></p>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
  </div>
-->



</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__($str), array('controller' => 'users', 'action' => $page)); ?> </li>
    <li><?php echo $this->Html->link(__('Add Appointment'), array('action' => 'add/'.$link)); ?></li>
    <li><?php echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
  </ul>
</div>
