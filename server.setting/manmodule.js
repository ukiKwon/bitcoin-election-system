$(document).ready(function(){
   $("#permitb").click(function(){
       var buttonVal=$("#permitb").val();
       //var post_data = "post_msg="+$("#post1_msg").val();
       $.ajax ({
           type:"POST",
           url:"./manModule.php",
           dataType:'json',
           data:{'action': buttonVal},
           success:function(data) {
               alert ("success.");
           }
       });
   });
   $("#showtx").click(function(){
       var buttonVal=$("#showtx").val();
       $.ajax ({
           type:"POST",
           url:"./manModule.php",
           dataType:'json',
           data:{'action': buttonVal},
           success:function(data) {
               alert ("success.");
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
               alert ("success.");
           }
       });
   });
});
