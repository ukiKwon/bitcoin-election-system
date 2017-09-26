$(document).ready(function(){
   $("#showRate").click(function(){
       var canVal=$("#candidate").val();
       $.ajax ({
           type:"POST",
           url:"./result/result.php",
           dataType:'json',
           data:{'candidate': canVal},
           success:function(data) {
               alert ("success.");
           }
       });
   });
});
