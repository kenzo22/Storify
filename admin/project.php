<?php
include "../global.php";

//Ȩ���ж�
 if($_SESSION['group']!=1)
 {
	 goto($rooturl,"��ȷ�����Թ���Ա��ݵ�½..",2);
 }

$tpl_file="../html/project_admin.htm";
$side_nav="<li><a href='".$rooturl."/admin/project.php'>��Ŀ����</a></li> <li><a href='".$rooturl."/admin/paper.php'>���Ĺ���</a></li><li><a href='".$rooturl."/admin/paper.php'>�û�����</a></li>";


if(empty($_GET)) //default
{
	$re=$DB->query("select * from ".$db_prefix."Project order by End desc");
	
	$i=0;
	$project="<table align='center' width='100%'> 
	          <tr ><td></td> <td align='right'> <a href='project.php?action=add'>������Ŀ</a></td> </tr>
	          <tr> <td >��Ŀ����</td> <td >����</td> </tr>";
    while($row=$DB->fetch_array($re))
    {
	   $i++;
	   $project.="<tr><td> $i. ".$row['Name']."</td> <td>   <a href='project.php?action=edit&Id=".$row['ID']."'>�༭</a> <a href='project.php?action=del&Id=".$row['ID']."' onclick='javascript:return confirm(\"ȷ��ɾ��?\");'>ɾ��</a> </td> </tr>";
	  
     }  
    $project.="</table>";
	
	$tpl_var=array("content_right"=>"project","side_nav"=>"side_nav");
	do_template($tpl_file,$tpl_var);
}

if($_GET['action']=="add")
{
  

  if($_POST['act']!="add")
  {
   $project="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'> </td></tr>
          <tr><td width='80px'>��Ŀ����</td> <td > <textarea TYPE=text' NAME='name' cols='50' rows=4></textarea> </td> </tr> 
		  <tr><td >��Ŀ��Դ</td> <td > <textarea TYPE=text' NAME='source' cols='50' rows=4></textarea> </td> </tr> 
		  <tr><td>��ʼʱ��  <td> <INPUT TYPE=text' NAME='start' size=12>  &nbsp;   (��ʽ:yyyyMMdd ��20007��3��4�� 20070304)</td> </tr>
          <tr><td>����ʱ��  <td> <INPUT TYPE=text' NAME='end' size=12>  &nbsp;     </td> </tr>
		  <tr><td >��ϸ����</td> <td > <textarea TYPE=text' NAME='Intro' cols='50' rows=10></textarea> </td> </tr> 
		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='�����Ŀ'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='����' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='add'> </tr>
		  </table></form>";
		 
    
    $tpl_var=array("content_right"=>"project","side_nav"=>"side_nav");
    do_template($tpl_file,$tpl_var);
  }

  else  //������
  {
	  $name=htmlspecialchars($_POST["name"]);
	  $source=htmlspecialchars($_POST["source"]);
	  $intro=htmlspecialchars($_POST["intro"]);
	  $start=htmlspecialchars($_POST["start"]);
	  $end=htmlspecialchars($_POST["end"]);
	  $DB->query("insert into ".$db_prefix."Project(Name,Source,Intro,Start,End) values('".$name."','".$source."','".$intro."','".$start."','".$end."')" );

	  goto("project.php","�����ɹ�");
  }

}


if($_GET['action']=="edit")
{
  if($_POST['act']!="edit")
   {
	 $id=intval($_GET['Id']);
	 $row=$DB->fetch_one_array("select * from ".$db_prefix."Project where ID=".$id);

     $list="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'>  ������Ŀ </tr>
          <tr><td width='80px'>��Ŀ����</td> <td > <textarea TYPE=text' NAME='name' cols='50' rows=4>".$row['Name']."</textarea> </td> </tr> 
		  <tr><td width='80px'>��Ŀ��Դ</td> <td > <textarea TYPE=text' NAME='source' cols='50' rows=3>".$row['Source']."</textarea> </td> </tr> 
		  <tr><td>��ʼʱ��  <td> <INPUT TYPE=text' NAME='start' size=12 value='".$row['Start']."'>  &nbsp;   (��ʽ:yyyyMMdd ��20007��3��4�� 20070304)</td> </tr>
          <tr><td>����ʱ��  <td> <INPUT TYPE=text' NAME='end' size=12  value='".$row['End']."'>  &nbsp;     </td> </tr>
		  <tr><td >��ϸ����</td> <td > <textarea TYPE=text' NAME='Intro' cols='50' rows=10>".$row['Intro']."</textarea> </td> </tr> 
		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='�޸���Ŀ'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='����' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='edit'> </tr>
		  </table></form>";

      
      $tpl_var=array("content_right"=>"list","side_nav"=>"side_nav");
      do_template($tpl_file,$tpl_var);
   }
   else
   {
	  $id=intval($_GET['Id']);
	  $name=htmlspecialchars($_POST["name"]);
	  $source=htmlspecialchars($_POST["source"]);
	  $start=htmlspecialchars($_POST["start"]);
	  $end=htmlspecialchars($_POST["end"]);
	  $intro=htmlspecialchars($_POST["Intro"]);
	  $DB->query("update ".$db_prefix."Project set Name='".$name."',Source='".$source."',Start='".$start."',End='".$end."',Intro='".$intro."' where ID=".$id.""  );

	  goto("project.php","�������");
    }
}

if($_GET['action']=="del")
{
   $id=intval($_GET['Id']);
   $DB->query("delete from ".$db_prefix."Project where ID=".$id);
   goto("project.php","�������");
}

?>