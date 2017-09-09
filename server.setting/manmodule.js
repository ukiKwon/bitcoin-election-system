$(document).ready(function(){
   $("#syncdb").click(function(){
       var buttonVal=$("#syncdb").val();
       //var post_data = "post_msg="+$("#post1_msg").val();
       $.ajax ({
           type:"POST",
           url:"./manModule.php",
           dataType:'json',
           data:{'action': syncdb},
           success:function(data) {
               alert ("질문이 입력되었습니다.");
           }
       });
   });
   $("#concan").click(function(){
       var buttonVal=$("#concan").val();
       $.ajax ({
           type:"POST",
           url:"./manModule.php",
           dataType:'json',
           data:{'action': buttonVal},
           success:function(data) {
               alert ("질문이 입력되었습니다.");
           }
       });
   });
   $("#genaddr").click(function(){
       var buttonVal=$("#genaddr").val();
       var post_data = $("#candidate").val();
       console.log(post_data);
       $.ajax ({
           type:"POST",
           url:"./manModule.php",
           dataType:'json',
           data:{'action': buttonVal, 'candidate': post_data},
           success:function(data) {
               alert ("질문이 입력되었습니다.");
           }
       });
   });
});
