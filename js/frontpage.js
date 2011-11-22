$(function(){	
  $('.login_top').attr('name', 'modal').attr('href', '#dialog');
  
  $('#login_awesome').attr('name', 'modal').attr('href', '#dialog');
  
  var sequence_val = 0;
  
  $('#story_more').live('click', function(e){
	e.preventDefault();
	sequence_val = sequence_val+4;
	var selElem = $('.time_range.selected');	
	var flag_val = $('.time_range').index(selElem);
	var getData = {flag:flag_val, sequence:sequence_val};
	$.ajax({
	  type: 'GET',
	  url: '/member/shufflestory.php',
	  data: getData, 
	  beforeSend:function() 
	  {
		var imgloading = $("<span><img src='/img/loading.gif' alt='正在加载' /></span>");
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
	var flag_val = $('.time_range').index(this);
	var getData = {flag:flag_val, sequence:0};
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
	
	//select all the a tag with name equal to modal
	$('a[name=modal]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = $(this).attr('href');
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		//$('#mask').fadeIn(1000);	
		//$('#mask').fadeTo("slow",0.8);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(1000); 
	
	});
	
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		
		$('#mask').hide();
		$('.window').hide();
	});		
	
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
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
