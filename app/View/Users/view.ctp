<?php echo  $this->Html->css('appo'); ?>
<?php echo $this->Html->css('clockpicker/assets/css/bootstrap.min.css');?>
<div class="users view">
    <h2><?php if($user["id"] == $tmp_user[0]["User"]["id"]){
                echo ('マイページ');}
              else{
                echo $tmp_user[0]["User"]["name"] . "さんの予定一覧";} ?>
    </h2>
    <dl style="float:left; width:300px;">
      <dt class="my_dt">ID</dt>
      <dd class="my_dd"><?php echo $tmp_user[0]["User"]["id"]; ?>
      <dt class="my_dt">Name</dt>
      <dd class="my_dd"><?php echo $tmp_user[0]["User"]["name"]; ?>
      <dt class="my_dt">E-mail</dt>
      <dd class="my_dd"><?php echo $tmp_user[0]["User"]["username"]; ?>
    </dl>

    <span>
            <?php //アイコン画像の出力  テーブルにデータがなかったらNO IMAGE
                if($image_param_su[0][0]['COUNT(user_id)'] == 0){
                  echo "<img src=../../app/webroot/img/images/user_icon_image.gif>";
                }
                elseif($login_user_image_params  != null){
                  echo "<img src=../../app/webroot/img/images/{$login_user_image_params[0]['images']['filename']}>";
                }else{
                  echo "<img src=../../app/webroot/img/images/user_icon_image.gif>";
                }
            ?>
    </span>

    <span style="margin-right:300px; width:300px; float:right;">
      <?php 
        echo $this->Form->create( 'Images', array('type'=>'file', 'enctype' => 'multipart/form-data',  'action' => 'imageAdd'));
        echo $this->Form->input('image', array( 'type' => 'file','style' => 'float:left;'));
        echo $this->Form->submit( __('Upload'),array('style' => 'float:right;'));
        echo $this->Form->end(); 
      ?>
    </span>



    <div style="clear:right;"></div>
    <h3>予定リスト</h3>
    <table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo __('Date'); ?></th>
        <th><?php echo __('Time'); ?></th>
        <th class="actions"><?php if($user["id"] == $tmp_user[0]["User"]["name"]) echo __('Actions'); ?></th>
    </tr>
<?php foreach ($appointments as $appointment): ?>
    <tr>
        <td><?php echo h($appointment['Appointment']['date']); ?></td>
        <td><?php echo h($appointment['Appointment']['start'] . " ~ " . $appointment['Appointment']['end']); ?></td>
        <td class="actions">
            <?php if($user["id"] == $tmp_user[0]["User"]["id"] || $user["admin"] == 1) echo $this->Form->postLink(__('削除'), array('controller' => 'appointments', 'action' => 'delete', $appointment['Appointment']['id']), null, __('本当に削除しますか？', $appointment['Appointment']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
    <li><?php echo $this->Html->link(__('HOME'), array('controller' => 'appointments', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('ログアウト'), array('action' => 'logout')); ?> </li>
        <li><?php echo $this->Html->link('パスワードの変更', array('controller' => 'users', 'action' => 'pass'))?></li>
        <li><?php echo $this->Html->link('名前の変更', array('controller' => 'users', 'action' => 'name'))?></li>
        <li><?php echo $this->Html->link('ユーザーの管理', array('controller' => 'users', 'action' => "admin/".$user['id'])); ?>
    </ul>

</div>
 