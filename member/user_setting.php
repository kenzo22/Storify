<?php
include "../global.php";
$uid=intval($_SESSION['uid']);

if($_POST[act]!='modify')
{
	$result=$DB->fetch_one_array("select * from story_user where id=".$uid);
	if(!empty($result['photo']))
	{
	  $userphoto="<img style='float:left;' width='90px' src='".$rooturl."/img/user/".$result['photo']."'> </img><div><a style='margin-left:10px;float:left' href='/storify/member/uploadphoto.php/'>更换头像</a></div>";
	}    
	else
	{
	  $userphoto="<a href='/storify/member/uploadphoto.php'>放你的头像上来</a>";
	}			
	$user_set = "<div class='inner' style='padding-top:50px;'>
				<form id='lzform' name='lzform' method='post'>
				   <table style='clear:both' width='100%' align='center' cellpadding='5'>
					 <tr>
					   <td valign='top' align='right'>名　号: </td>
					   <td valign='top'>
						 <input name='user_name' type='text' size='15' maxlength='15' value='".$result['username']."'/>
						 <br/>名号30天内只能修改一次。<br/>
					   </td>
					 </tr>
					 <tr>
					   <td valign='top' align='right'>头　像:</td>
					   <td valign='top'>".$userphoto."</td>
					 </tr>
					 <tr>  
					   <td valign='top' align='right'>个人介绍:</td> 
					   <td  colspan=2 ><textarea cols=80 rows=10 name='intro'>".$result['intro']."</textarea></td>  
					 </tr>
					 <tr>
					   <td align='right'>登录邮箱: </td>
					   <td valign='top'>
						 <span>xinxinzhang22@gmail.com</span>
						 <a href='/accounts/emailchange'>更改</a>
					   </td>
					 </tr>
					 <tr> 
					 <tr>
					   <td align='right'>登录密码: </td>
					   <td valign='top'>
						 <a href='/accounts/editpassword'>更改</a>
					   </td>
					 </tr>
					 <tr>
					   <td align='right'>手机号:</td>
					   <td>未绑定&nbsp;<a href='/accounts/phone/bind?ck=-NAn'>立即绑定</a></td>
					 </tr>
					 <tr>
						<td></td>
						<td><span><input name='pf_submit' type='submit' value='更新设置' tabindex='8'><input type='hidden' name='act' value='modify'></span></td>
					 </tr>
				   </table>
				   <div class='float_r'>
					 <span class='gact fright'>&gt;&nbsp;<a rel='nofollow' href='/accounts/suicide/'>删除帐号</a></span>
				   </div>
				</form>
				</div>";
	echo $user_set;
}
else
{
	$search = array ("'<script[^>]*?>.*?</script>'si","'<head[^>]*?>.*?</head>'si");
	$intro=addslashes(preg_replace($search,"",trim($_POST['intro'])));
	$DB->query("update ".$db_prefix."user set intro='".$intro."' where id=".$uid);
}


include "../include/footer.htm"
?>
