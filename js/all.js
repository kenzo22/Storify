var mastertabvar=new Object()
mastertabvar.baseopacity=0
mastertabvar.browserdetect=""

function showsubmenu(masterid, id)
{
  if(typeof highlighting!="undefined")
	clearInterval(highlighting);
  submenuobject=document.getElementById(id);
  mastertabvar.browserdetect=submenuobject.filters? "ie" : typeof submenuobject.style.MozOpacity=="string"? "mozilla" : "";
  hidesubmenus(mastertabvar[masterid]);
  submenuobject.style.display="block";
  instantset(mastertabvar.baseopacity);
  highlighting=setInterval("gradualfade(submenuobject)",50);
}

function hidesubmenus(submenuarray)
{
  for(var i=0; i<submenuarray.length; i++)
    document.getElementById(submenuarray[i]).style.display="none";
}

function instantset(degree)
{
  if (mastertabvar.browserdetect=="mozilla")
    submenuobject.style.MozOpacity=degree/100;
  else if (mastertabvar.browserdetect=="ie")
	submenuobject.filters.alpha.opacity=degree;
}


function gradualfade(cur2)
{
  if(mastertabvar.browserdetect=="mozilla" && cur2.style.MozOpacity<1)
	cur2.style.MozOpacity=Math.min(parseFloat(cur2.style.MozOpacity)+0.1, 0.99);
  else if(mastertabvar.browserdetect=="ie" && cur2.filters.alpha.opacity<100)
	cur2.filters.alpha.opacity+=10;
  else if(typeof highlighting!="undefined") //fading animation over
	clearInterval(highlighting);
}

function initalizetab(tabid)
{
  mastertabvar[tabid]=new Array();
  var menuitems=document.getElementById(tabid).getElementsByTagName("li");
  for(var i=0; i<menuitems.length; i++)
  {
	if (menuitems[i].getAttribute("rel"))
	{
	  menuitems[i].setAttribute("rev", tabid);
	  mastertabvar[tabid][mastertabvar[tabid].length]=menuitems[i].getAttribute("rel"); 
	  if(menuitems[i].className=="selected")
		showsubmenu(tabid, menuitems[i].getAttribute("rel"));
	  menuitems[i].getElementsByTagName("a")[0].onmouseover=function()
	  {
		showsubmenu(this.parentNode.getAttribute("rev"), this.parentNode.getAttribute("rel"));
	  }
	}
  }
}

initalizetab("maintab");

$(function()
{	   
  $('.load_more').live('click', function(e){
	e.preventDefault();
    var sort_val,name_val,subname_val,postData,
		more_id_val = $(this).attr('id'),
	    more_array = more_id_val.split('_'),
	    first_item_val = more_array[1];
	if($('.sort_type .now').text() == "最新")
	{
	  sort_val = "time";
	}
	else
	{
	  sort_val = "popular";
	}
	name_val = $('#maintab li.selected').text();
	subname_val = $('.submenustyle a.selected').text();
	postData = {from: "all", name: name_val, subname: subname_val, first_item: first_item_val, sort: sort_val};
	imgloading = $("<img src='/img/loading.gif' />");
	$.ajax({
			type: 'POST',
			url: '/member/loadmorestory.php',
			data: postData, 
			beforeSend:function() 
			{
			  $('.load_more').html(imgloading);
			},
			success: function(data){
				$('.more_content').remove();
				$('.sto_cover_list').append(data).after($('.more_content').remove());
			}
			});
  })
});