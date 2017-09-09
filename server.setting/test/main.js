$(document).ready(function(){
   $("#post_submit").click(function(){
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

});
