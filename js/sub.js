$(function(){$(".load_more").live("click",function(g){g.preventDefault();var c,a,b=$(this).attr("id"),f=b.split("_"),d=f[1];if($(".sort_type .now").text()=="最新"){c="time"}else{c="popular"}a={from:"sub",first_item:d,sort:c};imgloading=$("<img src='/img/loading.gif' />");$.ajax({type:"POST",url:"/member/loadmorestory.php",data:a,beforeSend:function(){$(".load_more").html(imgloading)},success:function(e){$(".more_content").remove();$(".sto_cover_list").append(e).after($(".more_content").remove())}})})});