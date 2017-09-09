$(document).ready(function(){
   $("#syncdb").click(function(){
       var post_data = "post_msg="+$("#post1_msg").val();
       $.ajax ({
           type:"POST",
           url:"./res.php",
           dataType:'json',
           data:{'post_msg': post_data},
           success:function(data) {
               alert ("질문이 입력되었습니다.");
           }
       });
   });
   $("#concan").click(function(){
       var post_data = "post_msg="+$("#post1_msg").val();
       $.ajax ({
           type:"POST",
           url:"./res.php",
           dataType:'json',
           data:{'post_msg': post_data},
           success:function(data) {
               alert ("질문이 입력되었습니다.");
           }
       });
   });
   $("#genaddr").click(function(){
       var post_data = $("#candidate").val();;//"<?php global $listCan_str; echojson_encode($listCan_str); ?>";
       console.log(post_data);
       $.ajax ({
           type:"POST",
           url:"./manModule.php",
           dataType:'json',
           data:{candidate : post_data},
           success:function(data) {
               alert ("질문이 입력되었습니다.");
           }
       });
   });
});
