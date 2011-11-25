function addBookmark() 
{
    var title='口立方';
    var url='http://www.koulifang.com';
    if(window.sidebar)
	{
      window.sidebar.addPanel(title, url, "");
    }
	else if(document.all) 
	{
      window.external.AddFavorite(url, title);
    } 
	else
	{
      alert('请按 Ctrl + D 为你的浏览器添加书签！');
    }
}

$(function(){
var browser_info = $.browser;
if(browser_info.msie) 
{
  browser_version = browser_info.version;
  if(parseInt(browser_version) < 7)
  {
    var n_support_content = "<div class='n_support'><div class='title_bar'><span>很抱歉，我们暂不支持此版本的浏览器</span></div><div><p>我们正在努力让口立方支持更多的浏览器版本，请您考虑使用下面的浏览器</p><ul><li><a href='http://www.firefox.com.cn' target='_blank'>Firefox(火狐浏览器)</a></li><li><a href='http://www.google.cn/chrome' target='_blank'>Chrome(谷歌浏览器)</a></li><li><a href='http://windows.microsoft.com/zh-CN/internet-explorer/products/ie/home' target='_blank'>IE浏览器(8.0及以上版本)</a></li></ul><a class='go_back' href='http://www.koulifang.com'>口立方主页 &raquo;</a></div></div><div class='n_mask'></div>";
	$('body').prepend(n_support_content);
	
	var winH = $(window).height();
	var winW = $(window).width();
              
	$('.n_support').css('top',  winH/2-$('.n_support').height()/2);
	$('.n_support').css('left', winW/2-$('.n_support').width()/2);
	$('.n_support').show();
	
	var mask_height = $(document).height();
	var mask_width = $(window).width();
	$('.n_mask').css({'width':mask_width,'height':mask_height});	
	$('.n_mask').show().css('opacity', '0.8');
  }
}

$('.person_li').bind('mouseover', function(e){
e.preventDefault();
$('.person_li').css('display', 'block');
});
$('.user_console').bind('mouseout', function(){
$('.person_li').slice(1, 4).css('display', 'none');
});
});