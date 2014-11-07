<?php echo  $this->Html->css('appo'); ?>

<table>
<tr>
  <th>id</th>
  <th>name</th>
 <th>timestamp</th>

<?php
  for($day=1; $day<=31; $day++)
    echo "<th>$day</th>";
?>
</tr>

<?php
  foreach ($result as $arr){
    echo '<tr>';
    echo "<td>{$arr['User']['id']}";
    echo "<td>{$arr['User']['name']}";
    echo "<td>{$arr['User']['timestamp']}</td>";
    echo '</tr>';
  }
?>
</table>
