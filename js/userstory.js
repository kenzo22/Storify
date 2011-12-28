Array.prototype.getUnique = function()
{
  var o = {}, i, e;
  for (i=0; e=this[i]; i++) {o[e]=1};
  var a=new Array();
  for (e in o)
  {a.push (e)};
  return a;
} 

String.prototype.len=function()
{
  return this.replace(/[^\x00-\xff]/g,"**").length;
}

var goto_top_type = -1, goto_top_itv = 0;  
  
function goto_top_timer()  
{  
  var moveby = 25,
      y = goto_top_type == 1 ? document.documentElement.scrollTop : document.body.scrollTop;     
  y -= Math.ceil(y * moveby / 100);  
  if (y < 0) 
  {  
    y = 0;  
  }   
  if(goto_top_type == 1) 
  {  
    document.documentElement.scrollTop = y;  
  }  
  else 
  {  
    document.body.scrollTop = y;  
  }   
  if (y == 0) 
  {  
    clearInterval(goto_top_itv);  
    goto_top_itv = 0;  
  }  
}  
  
function goto_top()  
{  
  if(goto_top_itv == 0) 
  {  
	if(document.documentElement && document.documentElement.scrollTop) 
	{  
	  goto_top_type = 1;  
	}  
	else if(document.body && document.body.scrollTop) 
	{  
	  goto_top_type = 2;  
	}  
	else 
	{  
	  goto_top_type = 0;  
	}  
	if(goto_top_type > 0) 
	{  
	  goto_top_itv = setInterval('goto_top_timer()', 20);  
	}  
  }  
} 

$(function(){
	$('body').prepend("<div id='mask'></div>");
	
	var gotop_ele = $('#go_top');
	gotop_ele.hide();
	$(window).bind("scroll", function(){
	  var st = $(document).scrollTop();
      (st > 0)? gotop_ele.show(): gotop_ele.hide();
	});
			  
	$('.follow').click(function(){
	  var follow_info = $(this).attr('id'),
	      follow_array = follow_info.split('_'),
	      userid = follow_array[2],
	      operation_val = $(this).text();
	  if('关注' == operation_val)
	  {
		operation_val = 'follow';
	  }
	  else
	  {
		operation_val = 'unfollow';
	  }
	  var postdata = {operation: operation_val, uid: userid}, temp='';
	  $.post('/member/useroperation.php', postdata,
		  function(data, textStatus)
		  {
			if('success'==textStatus)
			{
			  if(operation_val == 'follow')
			  {
				temp = $('.account_count .fans_count').text();
				$('.account_count .fans_count').text(parseInt(temp)+1);
			  }
			  else
			  {
				temp = $('.account_count .fans_count').text();
				$('.account_count .fans_count').text(parseInt(temp)-1);
			  }
			  $('.follow').toggle();
			}						
		  });
	}).hover(function(){
	  if($(this).text() == '已关注')
	  {
		$(this).text('取消关注');
	  }
	},
	function(){
	  if($(this).text() == '取消关注')
	  {
		$(this).text('已关注');
	  }
	});
	  
	  $('.follow_btn').click(function(){
		  var follow_btn_info = $(this).attr('id'),
		      info_array = follow_btn_info.split('_'),
		      userid = info_array[2],
		      operation_val = $(this).text();
		  if('关注' == operation_val)
		  {
			operation_val = 'follow';
		  }
		  else
		  {
			operation_val = 'unfollow';
		  }
		  var postdata = {operation: operation_val, uid: userid}, temp='';
		  $.post('/member/useroperation.php', postdata,
			  function(data, textStatus)
			  {
				if('success'==textStatus)
				{
				  if(operation_val == 'follow')
				  {
					temp = $('.usersfollowers .count').text();
					$('.usersfollowers .count').text(parseInt(temp)+1);
					$('.follower_list').append(data);
				  }
				  else
				  {
					var user_id=info_array[0];
					$('#follower_id_'+user_id).remove();
					temp = $('.usersfollowers .count').text();
					$('.usersfollowers .count').text(parseInt(temp)-1);
				  }
				  $('.follow_btn').toggle();
				}
				console.log(data);						
			  });
		}).hover(function(){
		  if($(this).text() == '已关注')
		  {
			$(this).text('取消关注');
		  }
		},
		function(){
		  if($(this).text() == '取消关注')
		  {
			$(this).text('已关注');
		  }
		});
		
	  $('#publish_container .load_more').live('click',function(e)
		{
		  e.preventDefault();
		  var id_val='',
		      more_id_val = $(this).attr('id'),
		      more_array = more_id_val.split('_'),
		      post_id_val = more_array[2],
		      first_item_val = more_array[0],
		      temp = first_item_val - 1,
		      postdata = {post_id: post_id_val, first_item: first_item_val},
			  imgloading = $("<img src='/img/loading.gif' />");
		  
		  $.ajax({
			type: 'POST',
			url: '/member/loadstoryitem.php',
			data: postdata, 
			beforeSend:function() 
			{
			  $('#publish_container .load_more').html(imgloading);
			},
			success: function(data){
				$('#more').remove();
				$('#weibo_ul').append(data);
				$('#weibo_ul li:gt('+temp+')').each(function()
				  {
					if($(this).hasClass('sina'))
					{
					  id_val = $(this).attr('id');
					  WB2.anyWhere(function(W){
					  W.widget.hoverCard({
						id: id_val,
						search: true
						}); 
					  });
					}
				  });
			}
			});
		});
		
	  WB2.anyWhere(function(W){
		W.widget.hoverCard({
			id: 'weibo_card_area',
			search: true
			}); 
		});
		
	  $('#weibo_ul li.sina').each(function()
	  {
		var id_val = $(this).attr('id');
		WB2.anyWhere(function(W){
		W.widget.hoverCard({
			id: id_val,
			search: true
			}); 
		});
	  });
	  
	  //select all the a tag with name equal to modal
		$('a[name=modal]').live('click', function(e){
			e.preventDefault();
			$('.publish-tweet').val('');
			$('#weibo_dialog .word_counter').text('140');
			if($(this).hasClass('sina'))
			{
			  $('#boxes #weibo_dialog #icon_flag').removeClass().addClass('sina16_icon');
			  if($('#boxes #weibo_dialog').hasClass('sina'))
			  {
				$('#pub_wrapper').show();
				$('.pub_imply_sina, .pub_imply_tencent').hide();
				if($(this).hasClass('repost_f'))
				{
				  $('#pub_text').text('转发').removeClass().addClass('sina');
				  $('#publish_title').text('转发微博');
				  if($(this).hasClass('is_repost'))
				  {
					var weibo_li = $(this).closest('li');
					var repost_txt = ('//@'+ weibo_li.find('.weibo_from_drop').text() + ': ' + weibo_li.find('.weibo_text_drop').text());
					repost_txt = repost_txt.substr(0, repost_txt.lastIndexOf('//@'));
					var repost_len=(280-repost_txt.len())/2;
					$('.publish-tweet').val(repost_txt);
					if(repost_len<0)
					{
					  var pub_tweet = $('.publish-tweet');
					  var i_max_len = pub_tweet.val().length+repost_len;
					  pub_tweet.attr('maxlength', i_max_len);
					  var i_cut_txt = pub_tweet.val().substr(0, i_max_len);
					  pub_tweet.val(i_cut_txt);
					  repost_len = 0;
					}
					$('#weibo_dialog .word_counter').text(Math.floor(repost_len));
				  } 
				}
				else
				{
				  $('#pub_text').removeClass().addClass('sina');
				  $('#pub_text').text('评论');
				  $('#publish_title').text('评论微博');
				}
			  }
			  else if(!$('#boxes #weibo_dialog').hasClass('disable'))
			  {
				$('#pub_wrapper, .pub_imply_tencent').hide();
				$('.pub_imply_sina').show();
				if($(this).hasClass('repost_f'))
				{
				  $('#publish_title').text('转发微博');
				}
				else
				{
				  $('#publish_title').text('评论微博');
				}
			  }
			}
			else if($(this).hasClass('tencent'))
			{
			  $('#boxes #weibo_dialog #icon_flag').removeClass().addClass('tencent16_icon');
			  if($('#boxes #weibo_dialog').hasClass('tencent'))
			  {
				$('#pub_wrapper').show();
				$('.pub_imply_sina, .pub_imply_tencent').hide();
				if($(this).hasClass('repost_f'))
				{
				  $('#pub_text').text('转播').removeClass().addClass('tencent');
				  $('#publish_title').text('转播微博');
				  if($(this).hasClass('is_repost'))
				  {
					var weibo_li = $(this).closest('li');
					var repost_txt = ('||'+ weibo_li.find('.weibo_from_drop').text() + '(@' + weibo_li.find('.weibo_from_drop').attr('href').replace(/http:\/\/t.qq.com\//,'') +'): ' + weibo_li.find('.weibo_text_drop').text());
					var match_array=repost_txt.match(/\|\|.*?\(@.*?\):[^|]+/g);
					repost_txt = repost_txt.replace(match_array[match_array.length-1],'')
					var repost_len=(280-repost_txt.len())/2;
					$('.publish-tweet').val(repost_txt);
					if(repost_len<0)
					{
					  var pub_tweet = $('.publish-tweet');
					  var i_max_len = pub_tweet.val().length+repost_len;
					  pub_tweet.attr('maxlength', i_max_len);
					  var i_cut_txt = pub_tweet.val().substr(0, i_max_len);
					  pub_tweet.val(i_cut_txt);
					  repost_len = 0;
					}
					$('#weibo_dialog .word_counter').text(Math.floor(repost_len));
				  } 
				}
				else
				{
				  $('#pub_text').removeClass().addClass('tencent');
				  $('#pub_text').text('评论');
				  $('#publish_title').text('评论微博');
				}
			  }
			  else if(!$('#boxes #weibo_dialog').hasClass('disable'))
			  {
				$('#pub_wrapper, .pub_imply_sina').hide();
				$('.pub_imply_tencent').show();
				if($(this).hasClass('repost_f'))
				{
				  $('#publish_title').text('转播微博');
				}
				else
				{
				  $('#publish_title').text('评论微博');
				}
			  }
			}
			var w_id = 'txt_'+ $(this).closest('li').attr('id');
			$('.publish-tweet').attr('id', w_id);
			
			var id = $(this).attr('href');

			var maskHeight = $(document).height(),
			    maskWidth = $(window).width(),
			    winH = $(window).height(),
			    winW = $(window).width();

			$('#mask').css({'width':maskWidth,'height':maskHeight});	
			$('#mask').show().css('opacity', '0.7');
			var scrollTop = $(document).scrollTop(),
			    scrollLeft = $(document).scrollLeft();
				  
			$(id).css('top',  winH/2-$(id).height()/2+scrollTop-100);
			$(id).css('left', winW/2-$(id).width()/2+scrollLeft);
			$(id).show(); 

		});

		$('.window .close').click(function (e) {
			e.preventDefault();
			$('#mask').hide();
			$('.window').hide();
		});		

		$('#mask').click(function () {
			$(this).hide();
			$('.window').hide();
		});	

	$('#embed_a').toggle(function(e){
	  e.preventDefault();
	  $('#embed_bar').slideDown("slow");
	  $('.arrow_up').css('display', 'inline-block');
	  $('.arrow_down').hide();
	  $('#embed_bar span .sto_embed').select();
	},
	function(e){
	  e.preventDefault();
	  $('#embed_bar').slideUp("slow");
	  $('.arrow_down').show();
	  $('.arrow_up').hide();
	  $('#embed_bar span .sto_embed').select();
	});
	
	$('.sto_embed').click(function(){
	  $(this).select();
	});
	
	$('#user_action').css('display', 'inline');
	
	/*$('.delete').click(function(e){
	  e.preventDefault();
	  var r=confirm("确定删除这个故事吗?");
	  if (r==true)
	  {
	    var post_id_val = $(this).attr('id').replace(/_delete/, ""),
	        getData = {post_id: post_id_val};
	    $.get('/member/removestory.php', getData,
	    function(data, textStatus)
	    {
		  if(textStatus == 'success')
		  {
            if($('#'+post_id_val+'_delete').hasClass('redirect'))
			{
			  self.location = '/user/'+data;
			}
			else
			{
			  var remove = $('#'+post_id_val+'_delete').closest('li');
			  //$('#'+post_id_val+'_delete').closest('li').remove();
			  remove.hide('slow', function(){ remove.remove(); });
			}
		  }
	    });
	  }
	});*/
	
	$('.delete').click(function(e){
	  e.preventDefault();
	  var r=confirm("确定删除这个故事吗?");
	  if (r==true)
	  {
	    var info = $(this).attr('id').split('_'),
		    uid_val = info[1],
			pid_val = info[2],
	        postData = {uid: uid_val, pid: pid_val};
	    $.post('/member/removestory.php', postData,
	    function(data, textStatus)
	    {
		  if(textStatus == 'success')
		  {
            var item = $('#delete_'+uid_val+'_'+pid_val);
			if(item.hasClass('redirect'))
			{
			  self.location = '/user/'+data;
			}
			else
			{
			  var remove = item.closest('li');
			  remove.hide('slow', function(){remove.remove();});
			}
		  }
	    });
	  }
	});
	
	$('.act_digg').click(function(e)
	{
	  e.preventDefault();
	  var temp_array = $(this).attr('id').split('_'),
          post_id_val = temp_array[2], 
	      getData = {post_id: post_id_val};
	  $.get('/member/diggoperation.php', getData,
	  function(data, textStatus)
	  {
		if(textStatus == 'success')
		{
		  if(data == 0)
		  {
		    alert('您已经投票过了');
		  }
		  else
		  {
			var temp = $('#digg_count_'+post_id_val).text();
			var digg_count = 1+parseInt(temp);
		    $('#digg_count_'+post_id_val).text(digg_count).attr('title', '累计赞'+digg_count+'次');
		  }
		}
	  });
	});
	
	$('.published-steps .tabs').click(function(event)
	{
	  var target = (event.target) ? $(event.target) : $(event.srcElement);
	  target = target.closest('button');
	  if (target.is('.tabs .post-tab'))
	  {
		$('.steps .notify-content').css('display', 'none');
		$('.steps .share-content').css('display', 'none');
		$('.steps .post-content').toggle();
		$('.post-content .sto_embed').select();
	  }
	  else if (target.is('.tabs .notify-tab'))
	  {
		$('.steps .post-content').css('display', 'none');
		$('.steps .share-content').css('display', 'none');
		$('.steps .notify-content').toggle();
		$('.tabs .notify-tab').blur();
	  }
	  else if (target.is('.tabs .share-tab'))
	  {
		$('.steps .post-content').css('display', 'none');
		$('.steps .notify-content').css('display', 'none');
		$('.steps .share-content').toggle();
		$('.tabs .share-tab').blur();
	  }
	});
	
	$('.notify-tweet').live('keyup', function(e){
	  var w_user_count = 0, t_user_count = 0;
	  $('.sina_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  w_user_count += $(this).next().text().len() +1;
		}
	  });
	  $('.tencent_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  t_user_count += $(this).next().attr('id').len() +2;
		}
	  });
	  var user_count = (w_user_count > t_user_count)?w_user_count:t_user_count;
      var word_remain=(280-$(this).val().len() - user_count)/2;
	  if(word_remain == 0)
	  {
		var max_len = $(this).val().length;
		$(this).attr('maxlength', max_len);
	  }
	  if(word_remain < 0)
	  {
		var max_len = $(this).val().length+word_remain;
		$(this).attr('maxlength', max_len);
		var cut_txt = $(this).val().substr(0, max_len);
		$(this).val(cut_txt);
		word_remain = 0;
	  }
	  $('.tweet_control .word_counter').text(Math.floor(word_remain));
	});
	
	$('.publish-tweet').live('keyup', function(e){
      var word_remain=(280-$(this).val().len())/2;
	  if(word_remain == 0)
	  {
		var max_len = $(this).val().length;
		$(this).attr('maxlength', max_len);
	  }
	  if(word_remain < 0)
	  {
		var max_len = $(this).val().length+word_remain;
		$(this).attr('maxlength', max_len);
		var cut_txt = $(this).val().substr(0, max_len)
		$(this).val(cut_txt);
		word_remain = 0;
	  }
	  $('#weibo_dialog .word_counter').text(Math.floor(word_remain));
	});
	
	//publish and repost part
	$('.btn_w_publish').live('click', function(e){
	  var w_content_val = $('.publish-tweet').val();
	  var id_val = $('.publish-tweet').attr('id').substr(6);
	  var ope_val;
	  if($('#pub_text').text() == '评论')
	  {
	    ope_val = 'comment';
	  }
	  else
	  {
	    ope_val = 'repost';
	  }
	  var postUrl, postData;
	  if($('#pub_text').hasClass('sina'))
	  {
	    postUrl = '/weibo/postweibo.php';
	  }
	  else
	  {
	    postUrl = '/tweibo/posttweibo.php';
	  }
	  postData = {operation: ope_val, id: id_val, weibo_content: w_content_val};

	  $.ajax({
	  type: 'POST',
	  url: postUrl,
	  data: postData, 
	  success: function(data)
	  {
		$('#mask').hide();
		$('.window').hide();
	  }
	  });
	});
	
	$('.tweet_btn').live('click', function(e){
	  e.preventDefault();
	  var weibo_content_val = '', tweibo_content_val = '';
	  $('.sina_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  weibo_content_val += $(this).next().text()+' ';
		}
	  });
	  $('.tencent_user .notify-user input').each(function()
	  {
	    if($(this).attr('checked'))
		{
		  tweibo_content_val += '@'+$(this).next().attr('id')+' ';
		}
	  });
	  
	  if(($('#tweibo_f').attr('checked')) && (tweibo_content_val != ''))
	  {
	      tweibo_content_val += $('.notify-tweet').val();
		  var postUrl, postData;
		  postUrl = '/tweibo/posttweibo.php';
		  postData = {operation: 'publish', weibo_content: tweibo_content_val};

		  $.ajax({
		  type: 'POST',
		  url: postUrl,
		  data: postData, 
		  success: function(data)
		  {
			$('.steps .notify-content').css('display', 'none');
		  }
		  });
	  }
	  
	  if(($('#weibo_f').attr('checked')) && (weibo_content_val != ''))
	  {
	      weibo_content_val += $('.notify-tweet').val();
		  var postUrl, postData;
		  postUrl = '/weibo/postweibo.php';
		  postData = {operation: 'publish', weibo_content: weibo_content_val};

		  $.ajax({
		  type: 'POST',
		  url: postUrl,
		  data: postData, 
		  success: function(data)
		  {
			$('.steps .notify-content').css('display', 'none');
		  }
		  });
	  }
	  if($('.steps .notify-content').is(':visible'))
	  {
	    $('.steps .notify-content').css('display', 'none');
	  }
	});
	
	//user comment part
	$('#reply_input').val('我想说...').addClass('imply_color');
	$('#reply_input').blur(function(){
	  if($(this).val() == '')
	  {
		$(this).val('我想说...').addClass('imply_color');
	  }
	}).focus(function(){
	  if($(this).val() == '我想说...' && $(this).hasClass('imply_color'))
	  {
		$(this).val('').removeClass('imply_color');
	  }
	});
	
	$('.reply_comment').live('click', function(e){
	  e.preventDefault();
	  var pre_text = '回复'+$(this).closest('li').find('.comment_author a').text()+': ';
	  $('#reply_input').val(pre_text).removeClass('imply_color').focus();
	});
	
	$('.post_comment').click(function(e){
	  e.preventDefault();
	  var comment_input = $('#reply_input');
	      temp_array = $(this).attr('id').split('_'),
	      user_id_val = temp_array[2],
		  post_id_val = temp_array[1],
	      content_val = comment_input.val();
	      postData = {post_id: post_id_val, user_id: user_id_val, comment_content: content_val};
	  if(content_val == '' || (content_val == '我想说...' && comment_input.hasClass('imply_color')))
	  {
	    return false;
	  }
	  $.post('/member/postcomment.php', postData,
	  function(data, textStatus)
	  {
	    if(textStatus == 'success')
	    {
		  $('#reply_input').val('我想说...').addClass('imply_color');
		  $('#comment_list').prepend(data);
		  $('#comment_list li:first').show('slow');
	    }
	  });
	});
	
	$('#reply_container .load_more').live('click', function(e){
	  e.preventDefault();
	  var imgloading = $("<img src='/img/loading.gif' />"),
	      temp_array = $(this).attr('id').split('_'),
		  post_id_val = temp_array[2],
		  first_val = temp_array[3],
	      postData = {post_id: post_id_val, first: first_val};
	  
	  $.ajax({
			type: 'POST',
			url: '/member/showcomments.php',
			data: postData, 
			beforeSend:function() 
			{
			  $('#reply_container .load_more').html(imgloading);
			},
			success: function(data){
			  $('#reply_container .load_more').closest('li').remove();
			  $('#comment_list').append(data);
			}
			});	  
	});
	
	$('.del_comment').live('click', function(e){
	  e.preventDefault();
	  var r=confirm("确定删除这条评论吗?");
	  if (r==true)
	  {
	    var remove = $(this).closest('li'),
		    cinfo = remove.attr('id').split('_'),
			uid_val = cinfo[1],
			cid_val = cinfo[2],
	        postData = {uid: uid_val, cid: cid_val};
	    $.post('/member/deletecomment.php', postData,
	    function(data, textStatus)
	    {
		  if(textStatus == 'success')
		  {
			remove.hide('slow', function(){ remove.remove(); });
		  }
	    });
	  }
	});

});
