$(function(){
$('#sina_weibo').click(function(e){
e.preventDefault();
var postdata;
if($(this).text() == '添加')
{
  postdata = {operation: 'add'};
  $.post('weibosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('weibosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#sina_weibo').text('添加');
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
  $.post('tweibosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('tweibosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#tencent_weibo').text('添加');
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
  $.post('doubansource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('doubansource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#douban_forum').text('添加');
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
  $.post('yupoosource.php', postdata,
  function(data, textStatus)
  {					
	self.location = data;
  });
}
else
{
  postdata = {operation: 'delete'};
  $.post('yupoosource.php', postdata,
  function(data, textStatus)
  {	
    if(textStatus == 'success')
	{
	  $('#yupoo_pic').text('添加');
	  $('.modify_notify').remove();
	  $('#source_ul').before(data);
	}
  });
}
});
});