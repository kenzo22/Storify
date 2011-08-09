<?php
include "../global.php";
?>

<div class='div_center' >
<span>为了您更好的使用该服务，请您选择要添加的信息源</span>
<ul>
  <li><a id='sina_weibo' href='#'>新浪微博</a></li>
  <li><a id='tencent_weibo' href='#'>腾讯微博</a></li>
  <li><a id='yupoo_pic' href='#'>又拍社区</a></li>
</ul>
<ul id='source_info'></ul>
</div>

<script>
$(function(){
$('#sina_weibo').click(function(e){
e.preventDefault();
var postdata;
$.post('weibosource.php', postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
});

$('#tencent_weibo').click(function(e){
e.preventDefault();
var postdata;
$.post('tweibosource.php', postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
});

$('#yupoo_pic').click(function(e){
e.preventDefault();
var postdata;
$.post('yupoosource.php', postdata,
		  function(data, textStatus)
		  {					
			self.location = data;
		  });
});
});
</script>

<?php
include "../include/footer.htm";
?>