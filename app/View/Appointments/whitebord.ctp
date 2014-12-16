<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Whiteboard Page</title>
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
  </head>

<body>
  <h2><?php echo ('出勤予定表 ~White Board Style~'); ?></h2>
    <div class="container" style="padding:20px 0">
     <form class="form-inline" style="margin-bottom:15px">
          <div class="form-group"><!--has-error-->
            <label for="in-time">出勤時間</label>
            <div>
              <input type="text" size="5" id="in_time" class="form-control" value="9:00">
            </div>
          </div>
          <div class="form-group"><!--has-error-->
            <label  for="out-time">退勤時間</label>
            <div>
              <input type="text" size="5" id="out_time" class="form-control" value="18:00">
            </div>
          </div>
         
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="button" class="btn btn-danger" id="delete">予定の削除</button> 
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <div class="btn-group"> 
  <p><strong>NOW MODE</strong></p>
     <button type="button" class="btn btn-primary" id="now_insert">INSERT</button> 
     <button type="button" class="btn btn-default active" id="now_delete">DELETE</button> 
   </div>

    </form>
   </div>
  <div class="container" style="padding:20px 0">
  <table class="table table-striped table-bordered table-hover">

  <div>

    <thead>
        <?php 
          $time_stamp = time();
          $now_days = date('t');
          $now_month  = date('m');
          $now_year   = date('Y');
          $youbi_suti = date("w",mktime(0,0,0,date("m",$time_stamp),1,date("Y",$time_stamp)));//数値（日曜=0）
          $youbi = array('0' => '日','1' => '月','2' => '火','3' => '水','4' => '木','5' => '金','6' => '土');
          
          //年と曜日の出力
          echo "<tr>";
          echo "<th>".$now_year."年"."</th>"; 
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
          echo "<th>".$now_month."月"."</th>"; 
          for($i=1;$i<$now_days+1;$i++){
            echo "<th>".$i."</th>";
          }
          echo "</tr>";
        ?>
      <tbody>

     
      <?php
          $param_su = 0;  //パラメータ数

               // debug($appointment_params);

          foreach ($user_names as $user_name){
          // debug($user_name);

            $style = $user_name['User']['id'] % 2;

            if($login_user_ids == $user_name['User']['id']){
              echo "<tr class=info>";
            }else if($style == 0){
              echo "<tr class=warning>";
            }
            else{
              echo "<tr>";
            }
            //ユーザー名の出力
              $user_id = $user_name['User']['id'];
            if($login_user_ids == $user_name['User']['id']){
              echo "<td>".h($user_name['User']['username'])."</td>";
            }else{
              echo "<td>".h($user_name['User']['username'])."</td>";
            }

            //ログインユーザーの出勤管理の出力
           // debug($now_month);
           // debug(intval(substr($appointment_params[$param_su]['Appointment']['date'],-5,2)));

            for($i = 1;$i<32; $i++){
            // debug($param_su);
            // debug($param_counts['0']['0']['COUNT(id)']);

           // debug(substr($appointment_params[$param_su]['Appointment']['date'],-5,2));
             // debug(intval(substr($appointment_params[$param_su]['Appointment']['date'],-5,2)));
              if($login_user_ids == $user_name['User']['id']){
                  if($param_su != $param_counts['0']['0']['COUNT(id)']){
                    if($now_month == substr($appointment_params[$param_su]['Appointment']['date'],-5,2)){
                      if($user_name['User']['id'] == $appointment_params[$param_su]['Appointment']['user_id'] && $i == intval(substr($appointment_params[$param_su]['Appointment']['date'],-2,2))){
                        if(intval(substr($appointment_params[$param_su]['Appointment']['start'],0,2)) < 12){

                          echo"<td style=color:red  class=login_user data-date=$now_year.$now_month.$i id=$user_id>"."○"."</td>";
                          $param_su++;

                        }else if(intval(substr($appointment_params[$param_su]['Appointment']['start'],0,2)) >= 12){
                          echo"<td style=color:green class=login_user data-date=$now_year.$now_month.$i id=$user_id>"." △"."</td>";
                          $param_su++;
                        }
                      }else{
                        echo"<td class=login_user data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>";
                      }     
                    }else{
                      $param_su++;
                      $i = 0;
                    }
                  }else{
                    echo"<td class=login_user data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>";
                  }
              }
            //他ユーザーの出勤管理の出力
              if($login_user_ids != $user_name['User']['id']){
                if($param_su != $param_counts['0']['0']['COUNT(id)']){
                  if($now_month == intval(substr($appointment_params[$param_su]['Appointment']['date'],-5,2))){
                    if($user_name['User']['id'] == $appointment_params[$param_su]['Appointment']['user_id'] && $i == intval(substr($appointment_params[$param_su]['Appointment']['date'],-2,2))){
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
                    $param_su++;
                  }
                }else{
                        echo"<td data-date=$now_year.$now_month.$i id=$user_id>"." "."</td>";     
                      }
              }
            }
          }
            echo "</tr>";
            // debug($user_id);
        ?>

          </tbody>
        </thead>
    </table>
    </div>


<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->  
 <!-- // <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> -->
    <script>
      $(function(){
              var day = new Date();
              var now_day = day.getDate();

                    // $('.login_user').click(function(){
                  // $('.login_user').one('click',insert);
                  $('.login_user').on('click',function(){
                    if($(this).html() != "○" && $(this).html() != "△"){
                      // function insert(){
                      var in_time     = $('#in_time').val();
                      var out_time    = $('#out_time').val();
                       console.log(now_day);
                       $appoint_day_elem = $(this);
                       var login_user = $appoint_day_elem.attr('id');
                       var appo_days = $appoint_day_elem.attr("data-date");
                       console.log(appo_days);
                       console.log(in_time);
                       if(appo_days.substr(8,2) > now_day){
                       // console.log(login_user);
                      if(in_time.length == 4){
                        if(in_time.substr(0,1) < 12){
                          var time_data = '○';          
                        }
                      }

                      if(in_time.length == 5){
                        if(in_time.substr(0,2) < 12){
                          var time_data = '○';          
                        }else{
                          var time_data = '△';
                        }
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
                        url: "/git_test/attendance_book/appointments/wbsAppoInsert",
                        type: "POST",
                        contentType: "application/json",
                        // dataType: "text",
                        data: JSON.stringify(param),
                        //    "user_id": login_user, 
                        //    "date": appo_days,
                        //    "start": in_time,
                        //    "end": out_time
                         // }),
                        success: function(){
                           // console.log(data);
                           alert("success");
                          // debug($this->request->data);
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                          console.log(XMLHttpRequest); // XMLHttpRequestオブジェクト
                          console.log(textStatus); // status は、リクエスト結果を表す文字列
                          console.log(errorThrown); // errorThrown は、例外オブジェクト
                          alert("格納失敗");
                        }
                      });//ajax2  
                    }//if(appo_days > now_day)
                  // });//.login_user.click function
                 }
               });
           
              

          //出勤予定の削除処理
          // $('#delete').click(function(){

            // var now_day = day.getDate();
            // $('#now_insert').removeClass('btn btn-primary').addClass('btn btn-default active');
            // $('#now_delete').removeClass('btn btn-default active').addClass('btn btn-danger');

             $('.login_user').dblclick(function(){
             // $('.login_user').on('click',del);
              // $('.login_user').off('click',insert);
             // function del(){ 
               $appoint_day_elem = $(this);
               var login_user = $appoint_day_elem.attr('id');
               var appo_days = $appoint_day_elem.attr("data-date");
              if(appo_days.substr(8,2) > now_day){
                if($(this).html() == "○" || $(this).html() == "△"){
                  $(this).html(" ");
                  // $(this).remove(html("○")); 
                var param = {
                           // "user_id": login_user, 
                           "date": appo_days
                      };                     

                      //出勤予定のデータベース削除処理
                      $.ajax({
                        url: "/git_test/attendance_book/appointments/wbsAppoDelete",
                        type: "POST",
                        contentType: "application/json",
                        // dataType: "text",
                        data: JSON.stringify(param),
                        success: function(){
                           // console.log(data);
                           alert("削除しました");
                  // debug($this->request->data);
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


        //モード変更処理
          $('.form-group').mouseover(function(){
            $('#now_insert').removeClass('btn btn-default active').addClass('btn btn-primary');
            $('#now_delete').removeClass('btn btn-danger').addClass('btn btn-default active');
               // $('.login_user').off('click',del);         
                 $('.login_user').change(function(){
                  alert("change");
                  // $('.login_user').on('click',insert);
                 });
          });

      });//一番最後
    </script>
</body>
</html>