<?php
require "../header.php";
?> 
<script>
$(function(){
/*$('#story_list').mouseover(function(e)
		{
		  if ($(e.target).is('.weibo_drop'))
		  {
		    $(e.target).find('.cross').css('visibility', 'visible');
		  }
		});
		
		$('#story_list').mouseout(function(e)
		{
		  if ($(e.target).is('.weibo_drop'))
		  {
		    $(e.target).find('.cross').css('visibility', 'hidden');
		  }
		});*/
		
		$('#story_list').hover(function(e){
		if ($(e.target).is('#t4'))
		{
		  //$('.cross').css('visibility', 'hidden');
		  $(e.target).children('.cross').css('visibility', 'visible');
		}
		else
		{
		  e.preventDefault();
		}
		},
		function(ev)
		{
		  if ($(ev.target).is('#t4'))
		  {
			$(ev.target).children('.cross').css('visibility', 'hidden');
		  }
		  else
		 {
		   e.preventDefault();
		 }
		});
});
</script>
<ul id='story_list' style='width:420px;'>
  <li class='weibo_drop' id='t1' style='background-color:yellow;'>
    <div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>
      <a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a>
    </div>
	<div class='story_wrapper'>
	  <div><span>微博测试微博测试微博测试微博测试</span></div>
	  <div id='story_signature'>
	    <div style='float:right;'>
		  <a><img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='http://tp4.sinaimg.cn/1642909335/50/5597136100/0'  border=0 /></a>
		</div>
		<div id='signature_text' style='margin-right:34px; padding-left:235px;'>
		  <a href='http://weibo.com/1624950264' target='_blank' style='display:block; height:16px;'><span>张辛欣</span></a>
		  <span style='height:16px;'>12:11</span>
		</div>
	  </div>
	</div>
  </li>
  <li class='weibo_drop' id='t2' style='background-color:yellow;'>
    <div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>
      <a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a>
    </div>
	<div class='story_wrapper'>
	  <div><span>微博测试微博测试微博测试微博测试</span></div>
	  <div id='story_signature'>
	    <div style='float:right;'>
		  <a><img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='http://tp4.sinaimg.cn/1642909335/50/5597136100/0'  border=0 /></a>
		</div>
		<div id='signature_text' style='margin-right:34px; padding-left:235px;'>
		  <a href='http://weibo.com/1624950264' target='_blank' style='display:block; height:16px;'><span>张辛欣</span></a>
		  <span style='height:16px;'>12:11</span>
		</div>
	  </div>
	</div>
  </li>
  <li class='weibo_drop' id='t3' style='background-color:yellow;'>
    <div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>
      <a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a>
    </div>
	<div class='story_wrapper'>
	  <div><span>微博测试微博测试微博测试微博测试</span></div>
	  <div id='story_signature'>
	    <div style='float:right;'>
		  <a><img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='http://tp4.sinaimg.cn/1642909335/50/5597136100/0'  border=0 /></a>
		</div>
		<div id='signature_text' style='margin-right:34px; padding-left:235px;'>
		  <a href='http://weibo.com/1624950264' target='_blank' style='display:block; height:16px;'><span>张辛欣</span></a>
		  <span style='height:16px;'>12:11</span>
		</div>
	  </div>
	</div>
  </li>
  <li class='weibo_drop' id='t4' style='background-color:yellow;'>
    <div class='cross' action='delete' style='visibility:hidden; padding-left:355px;'>
      <a><img src='/Storify/img/cross.png' border='0' onclick='remove_item(event)'/></a>
    </div>
	<div class='story_wrapper'>
	  <div><span>微博测试微博测试微博测试微博测试</span></div>
	  <div id='story_signature'>
	    <div style='float:right;'>
		  <a><img style='width: 32px; height: 32px; overflow: hidden; margin-top:2px;' src='http://tp4.sinaimg.cn/1642909335/50/5597136100/0'  border=0 /></a>
		</div>
		<div id='signature_text' style='margin-right:34px; padding-left:235px;'>
		  <a href='http://weibo.com/1624950264' target='_blank' style='display:block; height:16px;'><span>张辛欣</span></a>
		  <span style='height:16px;'>12:11</span>
		</div>
	  </div>
	</div>
  </li>
  
</ul>
<?php
include "../include/footer.htm";
?>