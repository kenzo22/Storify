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
$('.person_li').bind('mouseover', function(e){
e.preventDefault();
$('.person_li').css('display', 'block');
});
$('.user_console').bind('mouseout', function(){
$('.person_li').slice(1, 4).css('display', 'none');
});
});