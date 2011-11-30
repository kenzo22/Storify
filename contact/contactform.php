<?php
$html_title = "意见反馈 - 口立方";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<div class='inner'>
  <form id='fb_form' action="/processfeedback" method="post">
	<div class='title'> 请告诉我们您的建议 </div>  
	<div>您的姓名：</div>
	<input id='name_val' type="text" name="user_name" />
	<div>您的邮箱:</div>
	<input id='email_val' type="text" name="email" /><span class='form_tip' id='email_tip'></span>
	<div>您的意见:</div>
	<textarea id='fb_val' name="feedback" cols="80" rows="10"></textarea>
	<div id='btn_submit_fb'>
	  <a class='large blue awesome'>提交反馈 &raquo;</a> 
    </div>
  </form>
</div>
    
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";	 
?>
<script type='text/javascript' src='/js/feedback.js'></script>
</body>
</html>
