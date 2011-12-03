$(function(){
$('#sina_weibo').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('/accounts/weibosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('/accounts/weibosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#sina_weibo').text('添加');
	  $('#sina_weibo').prev().text('未添加帐号');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});

$('#tencent_weibo').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('/accounts/tweibosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('/accounts/tweibosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#tencent_weibo').text('添加');
	  $('#tencent_weibo').prev().text('未添加帐号');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});

$('#douban_forum').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('/accounts/doubansource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('/accounts/doubansource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#douban_forum').text('添加');
	  $('#douban_forum').prev().text('未添加帐号');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});

$('#yupoo_pic').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('/accounts/yupoosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('/accounts/yupoosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#yupoo_pic').text('添加');
	  $('#yupoo_pic').prev().text('未添加帐号');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});
});
