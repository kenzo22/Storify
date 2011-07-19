<?php
include "../global.php";
?>

<div class='div_center' >
<span>请您选择要添加的信息源</span>
<ul>
  <li id='sina_weibo'><span id="connectBtn"></span></li>
  <li id='tencent_weibo'><a>腾讯微博</a></li>
  <li id='renren'><a>人人网</a></li>
</ul>
<ul id='source_info'></ul>
</div>

<script type="text/javascript">
$(function(){
WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() 
{
  var cfg = {
              //key: '314237338',
			  key: '2417356638',
			  xdpath: 'http://story.com/storify/html/xd.html'
			};
  WB.connect.init(cfg);
  WB.client.init(cfg);
});



})

window.onload = function(){
WB.widget.base.connectButton(document.getElementById('connectBtn'),
{							     
 login:function(o)
 {
   //debugger;
   var postdata = {weibo_user_id: o.id};
   $.post('updateuser.php', postdata,
	 function(data, textStatus)
	 {
	   var $info = ("<li>添加了新浪微博</li>");
	   $('#source_info').append($info);								
	 });
 },
 logout:function()
 {

 }
});
}
</script>

<?php
include "../include/footer.htm";
?>