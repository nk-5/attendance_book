<div class="users view">
    <h2><?php  echo __('MyPage'); ?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($user['User']['id']); ?>
        </dd>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($user['User']['name']); ?>
        </dd>
        <dt><?php echo __('E-mail'); ?></dt>
        <dd>
            <?php echo h($user['User']['username']); ?>
        </dd>
    </dl>
    <h3>予約リスト</h3>
    <table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo __('Date'); ?></th>
        <th><?php echo __('Orders'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
<?php foreach ($appointments as $appointment): ?>
    <tr>
        <td><?php echo h($appointment['Appointment']['date']); ?></td>
        <td><?php echo h($appointment['Appointment']['order']); ?></td>
        <td class="actions">
            <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'appointments', 'action' => 'delete', $appointment['Appointment']['id']), null, __('Are you sure you want to delete # %s?', $appointment['Appointment']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('List Appointments'), array('controller' => 'appointments', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('Logout'), array('action' => 'logout')); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete?')); ?> </li>
    </ul>
</div>
