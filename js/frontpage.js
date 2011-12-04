function addBookmark() 
{
    var title='口立方', url='http://www.koulifang.com';
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
  $('.person_li').bind('mouseover', function(e)
  {
    e.preventDefault();
    $('.person_li').css('display', 'block');
  });
  $('.user_console').bind('mouseout', function()
  {
    $('.person_li').slice(1, 4).css('display', 'none');
  });
	
  $('.login_top').attr('name', 'modal').attr('href', '#dialog');
  
  $('#login_awesome').attr('name', 'modal').attr('href', '#dialog');
  
  var sequence_val = 0;
  
  $('#story_more').live('click', function(e){
	e.preventDefault();
	sequence_val = sequence_val+4;
	var selElem = $('.time_range.selected'),	
	    flag_val = $('.time_range').index(selElem),
	    getData = {flag:flag_val, sequence:sequence_val},
		imgloading = $("<span><img src='/img/loading.gif' alt='正在加载' /></span>");
	$.ajax({
	  type: 'GET',
	  url: '/member/shufflestory.php',
	  data: getData, 
	  beforeSend:function() 
	  {
		$('this').html(imgloading);
	  },
	  success: function(data)
	  {
		$('#pop_list').html(data);
	  }
	  });
  })
  
  $('.time_range').click(function(e){
	e.preventDefault();
	sequence_val = 0;
	$('.time_range').removeClass('selected');
	$(this).addClass('selected');
	var flag_val = $('.time_range').index(this),
	    getData = {flag:flag_val, sequence:0};
	$.ajax({
	  type: 'GET',
	  url: '/member/shufflestory.php',
	  data: getData, 
	  success: function(data)
	  {
		$('#pop_list').html(data);
	  }
	  });
  })
  
  $('#connectBtn').live('click', function(e)
  {
	e.preventDefault();
	$.post('/accounts/login/sina_auth.php', {}, 		
	function(data, textStatus)
	{
	  $('.window').hide();
	  self.location=data;
	});
  });
	
	$('a[name=modal]').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('href'),
		    winH = $(window).height(),
		    winW = $(window).width();    
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
		$(id).fadeIn(1000); 
	
	});
	
	$('.window .close').click(function (e) {
		e.preventDefault();
		$('.window').hide();
	});			
});

$(window).load(function() {
	$('#featured').orbit({
	  advanceSpeed: 8000,
	  bullets: true,
	  directionalNav: false
	});
});
