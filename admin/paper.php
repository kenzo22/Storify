<?php
include "../global.php";

//Ȩ���ж�
 if($_SESSION['group']!=1)
 {
	 goto($rooturl,"��ȷ�����Թ���Ա��ݵ�½..",2);
 }


$tpl_file="../html/project_admin.htm";

$side_nav="<li><a href='".$rooturl."/admin/project.php'>��Ŀ����</a></li> <li><a href='".$rooturl."/admin/paper.php'>���Ĺ���</a></li><li><a href='".$rooturl."/admin/user.php'>�û�����</a></li>";


if(empty($_GET)) //default
{
    $re=$DB->query("select * from ".$db_prefix."Paper order by Date desc");
	
	$i=0;
	$paper="<table align='center' width='100%'> 
	          <tr ><td></td> <td align='right'> <a href='paper.php?action=add'>��������</a></td> </tr>
	          <tr> <td >����</td> <td width='100px'>����</td> </tr>";
    while($row=$DB->fetch_array($re))
    {
	  $i++;
	  $paper.="<tr><td> $i. ".$row['Content']."</td> 
	           <td>  <a href='paper.php?action=edit&Id=".$row['ID']."'>�༭</a> 
	   <a href='paper.php?action=del&Id=".$row['ID']."' onclick='javascript:return confirm(\"ȷ��ɾ��?\");'>ɾ��</a> </td> </tr>";
	  
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
          <tr><td colspan=2 align='center'>  ���ӷ������� </tr>

          <tr><td width='80px'>����</td> <td > <textarea TYPE=text' NAME='content' cols='50' rows=4></textarea> </td> </tr> 
		  <tr><td>����ʱ��  <td> <INPUT TYPE=text' NAME='date' size=12>  &nbsp;   (��ʽ:yyyyMMdd ��20007��3��4�� 20070304)</td> </tr>
          <tr><td >ժҪ</td> <td > <textarea TYPE=text' NAME='abstract' cols='50' rows=10></textarea> </td> </tr> 

		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='������������'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='����' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='add'> </tr>
		  </table></form>";
		 
   $tpl_file="../html/paper_admin.htm";
   $tpl_var=array("content_right"=>"login","side_nav"=>"side_nav");
   do_template($tpl_file,$tpl_var);
  }

  else  //������
  {
	  $content=htmlspecialchars($_POST["content"]);
	  $date=htmlspecialchars($_POST["date"]);
	  $abstract=htmlspecialchars($_POST["abstract"]);
	  $DB->query("insert into ".$db_prefix."Paper(Content,Date,Abstract) values('".$content."','".$date."','".$abstract."')");

	  goto("paper.php","��ӳɹ�");
  }

}


if($_GET['action']=="edit")
{
  if($_POST['act']!="edit")
   {
     $id=intval($_GET['Id']);
	 $row=$DB->fetch_one_array("select * from ".$db_prefix."Paper where ID=".$id);

     $list="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'>  �༭���� </tr>
          
		  <tr><td width='80px'>����</td> <td > <textarea TYPE=text' NAME='content' cols='50' rows=4>".$row['Content']."</textarea> </td> </tr> 
		  <tr><td>����ʱ��  <td> <INPUT TYPE=text' NAME='date' size=12 value='".$row['Date']."'>  &nbsp;   (��ʽ:yyyyMMdd ��20007��3��4�� 20070304)</td> </tr>
          <tr><td >ժҪ</td> <td > <textarea TYPE=text' NAME='abstract' cols='50' rows=10>".$row['Abstract']."</textarea> </td> </tr> 

		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='�޸�'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='����' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='edit'> </tr>
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

	  goto("paper.php","�������");
   }
}

if($_GET['action']=="del")
{
  $id=intval($_GET['Id']);
   
   $DB->query("delete from ".$db_prefix."Paper where ID=".$id);
    goto("paper.php","�������");
}

?>