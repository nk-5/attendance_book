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
    <?php for($i=1; $i<15; $i++){
      echo "<div class=\"$i\">[$i]</div>";
    } ?>
  </ul>

  <p class="dragarea-p">時間帯</p>
  <div class="dragarea">
    <div class="order1"><p>AM~</p></div>
    <div class="order2"><p>PM~</p></div>
  </div>
    
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
