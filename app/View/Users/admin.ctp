<?php echo  $this->Html->css('appo'); ?>

<div class="users view">
    <h2><?php  echo __('管理画面'); ?></h2>
    <h3>ユーザーリスト</h3>
    <table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo __('id'); ?></th>
        <th><?php echo __('Name'); ?></th>
        <th><?php echo __('権限'); ?></th>
        <th class="actions"><?php echo __('削除'); ?></th>
        <th class="actions"><?php echo __('管理者権限'); ?></th>
        <th class="hoge"></th>
    </tr>
<?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo h($user['User']['id']); ?></td>
        <td><?php echo h($user['User']['name']); ?></td>
        <td><?php if($user['User']['admin']) echo '管理者';
else echo '一般ユーザー';?></td>
        <td class="actions">
            <?php echo $this->Form->postLink(__('削除'), array('controller' => 'users', 'action' => 'delete', $user['User']['id'], $user['User']['username'], $own_id), null, __('本当に削除しますか？')); ?>
        </td>
        <td class="actions"><?php echo $this->Form->postLink(__('管理者にする'), array('controller' => 'users', 'action' => 'administrate', $user['User']['id'], $own_id), null, __('本当に管理者にしますか？')); ?>
        </td>
        <td class="actions"><?php echo $this->Form->postLink(__('解除'), array('controller' => 'users', 'action' => 'unadministrate', $user['User']['id'], $own_id), null, __('本当に管理者権限を解除しますか？')); ?>
    </tr>
<?php endforeach; ?>
    </table>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
    <li><?php echo $this->Html->link(__('予定一覧'), array('controller' => 'appointments', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('ログアウト'), array('action' => 'logout')); ?> </li>
    </ul>
</div>
