<?php
include "../global.php";

//权限判断
 if($_SESSION['group']!=1)
 {
	 goto($rooturl,"请确认你以管理员身份登陆..",2);
 }


$tpl_file="../html/project_admin.htm";

$side_nav="<li><a href='".$rooturl."/admin/project.php'>项目管理</a></li> <li><a href='".$rooturl."/admin/paper.php'>论文管理</a></li><li><a href='".$rooturl."/admin/user.php'>用户管理</a></li>";


if(empty($_GET)) //default
{
    $re=$DB->query("select * from ".$db_prefix."Paper order by Date desc");
	
	$i=0;
	$paper="<table align='center' width='100%'> 
	          <tr ><td></td> <td align='right'> <a href='paper.php?action=add'>新增论文</a></td> </tr>
	          <tr> <td >论文</td> <td width='100px'>管理</td> </tr>";
    while($row=$DB->fetch_array($re))
    {
	  $i++;
	  $paper.="<tr><td> $i. ".$row['Content']."</td> 
	           <td>  <a href='paper.php?action=edit&Id=".$row['ID']."'>编辑</a> 
	   <a href='paper.php?action=del&Id=".$row['ID']."' onclick='javascript:return confirm(\"确定删除?\");'>删除</a> </td> </tr>";
	  
     }  
    $paper.="</table>";
	$tpl_var=array("content_right"=>"paper","side_nav"=>"side_nav");
	do_template($tpl_file,$tpl_var);
}

if($_GET['action']=="add")
{
  if($_POST['act']!="add")
  {
  $login="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'>  增加发表论文 </tr>

          <tr><td width='80px'>论文</td> <td > <textarea TYPE=text' NAME='content' cols='50' rows=4></textarea> </td> </tr> 
		  <tr><td>发表时间  <td> <INPUT TYPE=text' NAME='date' size=12>  &nbsp;   (格式:yyyyMMdd 如20007年3月4号 20070304)</td> </tr>
          <tr><td >摘要</td> <td > <textarea TYPE=text' NAME='abstract' cols='50' rows=10></textarea> </td> </tr> 

		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='新增论文内容'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='返回' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='add'> </tr>
		  </table></form>";
		 
   $tpl_file="../html/paper_admin.htm";
   $tpl_var=array("content_right"=>"login","side_nav"=>"side_nav");
   do_template($tpl_file,$tpl_var);
  }

  else  //表单处理
  {
	  $content=htmlspecialchars($_POST["content"]);
	  $date=htmlspecialchars($_POST["date"]);
	  $abstract=htmlspecialchars($_POST["abstract"]);
	  $DB->query("insert into ".$db_prefix."Paper(Content,Date,Abstract) values('".$content."','".$date."','".$abstract."')");

	  goto("paper.php","添加成功");
  }

}


if($_GET['action']=="edit")
{
  if($_POST['act']!="edit")
   {
     $id=intval($_GET['Id']);
	 $row=$DB->fetch_one_array("select * from ".$db_prefix."Paper where ID=".$id);

     $list="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'>  编辑论文 </tr>
          
		  <tr><td width='80px'>论文</td> <td > <textarea TYPE=text' NAME='content' cols='50' rows=4>".$row['Content']."</textarea> </td> </tr> 
		  <tr><td>发表时间  <td> <INPUT TYPE=text' NAME='date' size=12 value='".$row['Date']."'>  &nbsp;   (格式:yyyyMMdd 如20007年3月4号 20070304)</td> </tr>
          <tr><td >摘要</td> <td > <textarea TYPE=text' NAME='abstract' cols='50' rows=10>".$row['Abstract']."</textarea> </td> </tr> 

		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='修改'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='返回' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='edit'> </tr>
		  </table></form>";

      
      $tpl_var=array("content_right"=>"list","side_nav"=>"side_nav");
      do_template($tpl_file,$tpl_var);
   }
   else
   {
	  $id=intval($_GET['Id']);
	    
	  $content=htmlspecialchars($_POST["content"]);
	  $date=htmlspecialchars($_POST["date"]);
	  $abstract=htmlspecialchars($_POST["abstract"]);
	  $DB->query("update ".$db_prefix."Paper set Content='".$content."',Date='".$date."',Abstract='".$abstract."' where ID=".$id.""  );

	  goto("paper.php","操作完成");
   }
}

if($_GET['action']=="del")
{
  $id=intval($_GET['Id']);
   
   $DB->query("delete from ".$db_prefix."Paper where ID=".$id);
    goto("paper.php","操作完成");
}

?>