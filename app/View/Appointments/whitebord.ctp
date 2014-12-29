<?php echo  $this->Html->css('appo'); 
    echo $this->Html->css('clockpicker/assets/css/bootstrap.min.css');
    echo $this->Html->css('clockpicker/dist/bootstrap-clockpicker.min.css');
    echo $this->Html->script('clockpicker/assets/js/bootstrap.min.js');
    echo $this->Html->script('clockpicker/dist/bootstrap-clockpicker.min.js');
?>

<?php

 $ym = isset($_GET['ym']) ? $_GET['ym'] : date('Y-m');

  $time_stamp = strtotime($ym . "-01");

  if($time_stamp === false){
    $time_stamp = time();
  }

    $today      = date('d');
    $now_date   = date('Y-m-t',$time_stamp);
    $now_days   = date('t',$time_stamp);
    $now_month  = date('m',$time_stamp);
    $now_year   = date('Y',$time_stamp);
    $youbi_suti = date("w",mktime(0,0,0,date("m",$time_stamp),1,date("Y",$time_stamp)));//数値（日曜=0）
    $youbi = array('0' => '日','1' => '月','2' => '火','3' => '水','4' => '木','5' => '金','6' => '土');

    $prev = date('Y-m',mktime(0,0,0,date('m',$time_stamp)-1,1,date('Y',$time_stamp)));
    $next = date('Y-m',mktime(0,0,0,date('m',$time_stamp)+1,1,date('Y',$time_stamp)));
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Whiteboard Page</title>
    <style type="text/css">
      .container{
        display: inline;
      }
    </style>
  </head>


<body>
  <h2 position="center"><?php echo ('出勤予定表 ~White Board Style~'); ?></h2>

  <!--予定追加のメッセージ-->
    <?php 
      if($today <= 10){
        echo $this->Session->flash('two');  
      }elseif($today > 10 && $today <= 20){
        echo $this->Session->flash('three');  
      }elseif($today > 20 &&  $today <= $now_days){
        echo $this->Session->flash('one');   
      }
    ?>

<!--予定時間フォーム-->
  <div class="container" style="padding:20px;">
    <span style="float:left;">
     <form class="form-inline" style="width:700px;">
          <div class="form-group">
            <label for="in-time">出勤時間</label>
            <div>
              <input type="text" size="5" id="in_time" class="clockpicker" data-autoclose="true" value="09:00">
            </div>
          </div>
          <div class="form-group">
            <label  for="out-time">退勤時間</label>
            <div>
              <input type="text" size="5" id="out_time" class="clockpicker" data-autoclose="true" value="18:00">
            </div>
          </div>
          <div class="form-group">
            <button type="button" id="operation" class="btn btn-primary">操作方法</button>
         </div>

          <span class="form-group"> 
            <span id="process">
              <p>クリック = 予定の追加</p>
              <p>ダブルクリック = 予定の削除</p>
              <p>※操作は現在の日にちより上の予定にしか出来ません。</p>
            </span>
          </span>
     </form>
    </span>

    <span style="float:right; margin-right:300px;">
      <?php 
        echo $this->Form->create( 'Images', array('type'=>'file', 'enctype' => 'multipart/form-data',  'action' => 'imageAdd'));
        echo $this->Form->input( 'image', array( 'type' => 'file','style' => 'float:left;'));
        echo $this->Form->submit( __('Upload'),array('style' => 'float:right;'));
        echo $this->Form->end(); 
      ?>
    </span>
  </div>
<br><br><br>

      <span class="container" style="padding:20px 0;" class="row">
        <span class="col-sm-2"></span>
         <a href="?ym=<?php echo $prev; ?>"><button type="button" id="prev" class="btn btn-success col-sm-1"><i class="glyphicon glyphicon-chevron-left"></i> 前月</button></a>
        <span class="col-sm-2"></span>
        <span class="col-sm-2"></span>
        <span class="col-sm-2"></span>
        <a href="?ym=<?php echo $next; ?>"><button type="button" id="next" class="btn btn-success col-sm-1">翌月<i class="glyphicon glyphicon-chevron-right"></i></button></a>
        <span class="col-sm-2"></span>
      </span>


  <div class="container" style="padding:20px 0">
    <table class="table table-striped table-bordered table-hover">
    <div>
      <thead>
        <?php   

        // debug(realpath("../")."/Controller/appointments/wbsAppoInsert");
          //年と曜日の出力
          echo "<tr>";
          echo "<th size=36px></th>";
          echo "<th size=20px>".$now_year."年"."</th>"; 
          for($i=0;$i<$now_days;$i++){
            if($youbi_suti == 0){
              echo "<th style=color:red>".$youbi[$youbi_suti]."</th>";
            }else if($youbi_suti == 6){
              echo "<th style=color:blue>".$youbi[$youbi_suti]."</th>";
            }else{
              echo "<th>".$youbi[$youbi_suti]."</th>";
            }
            $youbi_suti++;
            if($youbi_suti == 7){
              $youbi_suti = 0;
            }
          }
          echo "</tr>";

          //月と日にちの出力
          echo "<tr>";
          echo "<th size=36px></th>";
          echo "<th>".$now_month."月"."</th>"; 
          for($i=1;$i<$now_days+1;$i++){
            echo "<th>".$i."</th>";
          }
          echo "</tr>";
        ?>

      <tbody>
       <?php
          $param_su = 0;  //予約パラメータ数
          $user_id = $login_user_ids;//tdに入れるログインユーザーのid
    
      //ログインユーザー出勤管理表出力
          echo "<tr class=info>";

      //アイコン画像の出力  テーブルにデータがなかったらNO IMAGE
        if($image_param_su[0][0]['COUNT(user_id)'] == 0){
          echo "<td size=36px><img src=../app/webroot/img/images/user_icon_noimage.gif></td>";
        }
        elseif($login_user_image_params  != null){
          echo "<td size=36px><img src=contents/{$login_user_image_params[0]['images']['filename']}></td>";
        }else{
          echo "<td size=36px><img src=../app/webroot/img/images/user_icon_noimage.gif></td>";
        }


          echo "<td size=20px>".h($user_names[$login_user_ids -1]['User']['username'])."</td>";

          for($i = 1;$i<$now_days+1; $i++){
            if($param_su != $login_user_param_count[0][0]['COUNT(user_id)']){
              if($now_year == intval(substr($login_user_params[$param_su]['appointments']['date'],0,4))){
                if($now_month == intval(substr($login_user_params[$param_su]['appointments']['date'],-5,2))){
                  if($i == intval(substr($login_user_params[$param_su]['appointments']['date'],-2,2))){
                    if(intval(substr($login_user_params[$param_su]['appointments']['start'],0,2)) < 12){
                      echo"<td style=color:red;cursor:pointer;  class=login_user data-date=$now_year.$now_month.$i id=$user_id>"."○"."</td>";
                      $param_su++;
                    }else if(intval(substr($login_user_params[$param_su]['appointments']['start'],0,2)) >= 12){
                      echo"<td style=color:green;cursor:pointer; class=login_user data-date=$now_year.$now_month.$i id=$user_id style=cursor:pointer;>"." △"."</td>";
                      $param_su++;
                    }

                  }else{
                    echo"<td class=login_user data-date=$now_year.$now_month.$i id=$user_id style=cursor:pointer;>"." "."</td>";
                  }

                }else{
                  if($login_user_params[$param_su]['appointments']['date'] < $now_date){
                    $i = 0;
                  }else if($login_user_params[$param_su]['appointments']['date'] > $now_date){
                    echo"<td class=login_user data-date=$now_year.$now_month.$i id=$user_id style=cursor:pointer>"." "."</td>";
                  }
                  $param_su++;
                }

              }else{
                if($login_user_params[$param_su]['appointments']['date'] < $now_date){
                  $i = 0;
                }else if($login_user_params[$param_su]['appointments']['date'] > $now_date){
                  echo"<td class=login_user data-date=$now_year.$now_month.$i id=$user_id style=cursor:pointer>"." "."</td>";
                }
                $param_su++;

              }
            }else{
                    echo"<td class=login_user data-date=$now_year.$now_month.$i id=$user_id style=cursor:pointer;>"." "."</td>";
            }
          }
          echo "</tr>";
      
      //他ユーザーの出勤管理の出力
          $param_su = 0;

          foreach ($user_names as $user_name){
            if($login_user_ids != $user_name['User']['id']){
              $style = $user_name['User']['id'] % 2;

              if($style == 0){
                echo "<tr class=warning>";
              }
              else{
                echo "<tr>";
              }

            $user_id = $user_name['User']['id'];//tdに入れるユーザーのid

         //アイコン画像の出力
          if($image_param_su[0][0]['COUNT(user_id)'] != 0){
            for($i = 0;$i < $image_param_su[0][0]['COUNT(user_id)'];$i++){
              if($image_params[$i]['Image']['user_id'] == $user_name['User']['id']){
                echo "<td size=36px><img src=contents/{$image_params[$i]['Image']['filename']}></td>";  
                break;
              }
            }
          }
          //テーブルにデータがなかったらNO IMAGE
          if($image_param_su[0][0]['COUNT(user_id)'] == 0 || $i == $image_param_su[0][0]['COUNT(user_id)']){
            echo "<td size=36px><img src=../app/webroot/img/images/user_icon_noimage.gif></td>";
          }

            //ユーザー名の出力
            if($login_user_ids != $user_name['User']['id']){
              echo "<td>".h($user_name['User']['username'])."</td>";
            }
          
            for($i = 1;$i<$now_days+1; $i++){
              if($param_su != intval($param_counts['0']['0']['COUNT(id)'])){
                if($appointment_params[$param_su]['Appointment']['user_id'] == $user_name['User']['id']){
                  if($now_year == intval(substr($appointment_params[$param_su]['Appointment']['date'],0,4))){
                    if($now_month == intval(substr($appointment_params[$param_su]['Appointment']['date'],-5,2))){
                      if($i == intval(substr($appointment_params[$param_su]['Appointment']['date'],-2,2))){
                        if(intval(substr($appointment_params[$param_su]['Appointment']['start'],0,2)) < 12){
                          echo"<td style=color:red data-date=$now_year.$now_month.$i id=$user_id>"." ○"."</td>";
                          $param_su++;             
                  
                         }else if(intval(substr($appointment_params[$param_su]['Appointment']['start'],0,2)) >= 12){
                           echo"<td style=color:green data-date=$now_year.$now_month.$i id=$user_id>"." △"."</td>";
                           $param_su++;
                          }

                      }else{
                        echo"<td data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>"; 
                      }
                    
                    }else{
                     if($appointment_params[$param_su]['Appointment']['date'] < $now_date){
                       $i = 0;
                     }else if($appointment_params[$param_su]['Appointment']['date'] > $now_date){
                      echo"<td data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>";     
                     }
                     $param_su++;     
                  }
                
                }else{
                  if($appointment_params[$param_su]['Appointment']['date'] < $now_date){
                    $i = 0;
                  }else if($appointment_params[$param_su]['Appointment']['date'] > $now_date){
                    echo"<td data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>";     
                  }
                  $param_su++;    
                }
              
              }else{
                if($appointment_params[$param_su]['Appointment']['user_id'] < $user_name['User']['id']){ 
                  $param_su++;   
                 $i = 0;
                }

                if($i<$now_days+1 && $appointment_params[$param_su]['Appointment']['user_id'] > $user_name['User']['id']){
                   echo"<td data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>"; 
                }
             }
            
          }else{
            echo"<td data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>";          
          }
        }
       }
       //ログインユーザー分の予約数を増やす & アイコン数を増やす
      if($login_user_ids == $user_name['User']['id']){
        $param_su = $param_su + intval($login_user_param_count[0][0]['COUNT(user_id)']);
      }
    }
    echo "</tr>";
?>

          </tbody>
        </thead>
    </table>
  </div>

    <script>
      $(function(){

       // var path = location.pathname.substr(1).split('/');
       // console.log(path);
       // path.pop();
       // path.pop();
       // location.pathname=path.join('/');

      //  console.log(path);

      // console.log(path[0]+'/'+path[1]);

      // if(path[0] == 'attendance_book'){
      //   var insert_path = path+"wbsAppoInsert";
      //   var delete_path = path+"wbsAppoDelete";
      // }else{
        // var insert_path = path[0]+'/'+path[1]+"/appointments/wbsAppoInsert";
        var insert_path = "wbsAppoInsert"
        var delete_path = "wbsAppoDelete";
      // }

      console.log(insert_path);
      console.log(delete_path);


        $('.clockpicker').clockpicker();

              var date = new Date();
              var now_day   = date.getDate();
              var now_month = date.getMonth()+1;
              var now_year  = date.getFullYear();
              var now_date = String(now_year)+"."+String(now_month)+"."+String(now_day);
              Number(now_date);

                  $('.login_user').on('click',function(){
                    if($(this).html() != "○" && $(this).html() != "△"){
                      // function insert(){
                      var in_time     = $('#in_time').val();
                      var out_time    = $('#out_time').val();
                       $appoint_day_elem = $(this);
                       var login_user = $appoint_day_elem.attr('id');
                       var appo_days = $appoint_day_elem.attr("data-date");
                       var appo_time = Date.parse(appo_days);
                       var now_time = Date.parse(date);
                       console.log(appo_time);
                       console.log(now_time);
                       console.log(appo_days.substr(8,2));
                       console.log(now_date);


                       if(appo_time > now_time){
                
                          if(in_time.substr(0,2) < 12){
                            var time_data = '○';          
                          }else{
                            var time_data = '△';
                          }
                      

                      if(time_data == '○'){
                        $(this).html(time_data),$(this).css('color','red');                      
                      }else{
                        $(this).html(time_data),$(this).css('color','green');                      
                      }

                      var param = {
                           "user_id": login_user, 
                           "date": appo_days,
                           "start": in_time,
                           "end": out_time,
                      };

                      //出勤予定のデータベース格納処理
                      $.ajax({
                        // url: "/git_test/attendance_book/appointments/wbsAppoInsert",                  
                        url: insert_path,
                        // url: "/wbsAppoInsert",
                        type: "POST",
                        contentType: "application/json",
                        // dataType: "text",
                        data: JSON.stringify(param),

                        success: function(){
                           // alert("success");
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                          console.log(XMLHttpRequest); // XMLHttpRequestオブジェクト
                          console.log(textStatus); // status は、リクエスト結果を表す文字列
                          console.log(errorThrown); // errorThrown は、例外オブジェクト
                          alert("格納失敗");
                        }
                      });//ajax2  
                    }//if(appo_days > now_day)
                   }
               });
           
          //出勤予定の削除処理
             $('.login_user').dblclick(function(){

               $appoint_day_elem = $(this);
               var login_user = $appoint_day_elem.attr('id');
               var appo_days = $appoint_day_elem.attr("data-date");
              if(appo_days.substr(8,2) > now_day){
                if($(this).html() == "○" || $(this).html() == "△"){
                  $(this).html(" ");
                var param = {
                           "date": appo_days
                      };                     

                      //出勤予定のデータベース削除処理
                      $.ajax({
                        url: "/git_test/attendance_book/appointments/wbsAppoDelete",
                        // url: delete_path,
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(param),
                        success: function(){
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                          console.log(XMLHttpRequest); // XMLHttpRequestオブジェクト
                          console.log(textStatus); // status は、リクエスト結果を表す文字列
                          console.log(errorThrown); // errorThrown は、例外オブジェクト
                          alert("削除失敗");
                        }
                      });//ajax2  
                      }//html.val() == ○ or △
                    }//if(appo_days > now_day)
                  });//.login_user.click function
               // }
          // });//delete.click fuction

          //操作説明処理
          $('#process').hide();
          $('#operation').click(function(){
            $('#process').toggle();
          });
      });//一番最後
    </script>
</body>
</html>