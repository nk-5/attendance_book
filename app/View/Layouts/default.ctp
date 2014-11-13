<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset("utf-8"); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
    echo $this->fetch('script');

    echo $this->Html->script('fullcalendar/lib/moment.min.js');
    echo $this->Html->css('fullcalendar/fullcalendar.css');
    echo $this->Html->script('fullcalendar/lib/jquery.min.js');
    echo $this->Html->script('fullcalendar/fullcalendar.min.js');
    //echo $this->Html->script('fullcalendar/gcal.js');
    echo $this->Html->script('jquery-ui/development-bundle/ui/jquery.ui.core.js');
    echo $this->Html->script('jquery-ui/development-bundle/ui/jquery.ui.datepicker.js');
    echo $this->Html->css('jquery-ui/css/start/jquery-ui-1.9.2.custom.css');
?>

</head>
<body>
	<div id="container">
    <div id="header">
      <div id="header_menu">
      <?php
        if(isset($user)):
          echo $this->Html->link('ログアウト','/users/logout');
        else:
          echo $this->Html->link('ログイン','/users/login');
          echo "   ";
          echo $this->Html->link('新規登録','/users/add');
        endif;
?>
</div>

		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>
