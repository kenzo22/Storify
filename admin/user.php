<?php
include "../global.php";

//Ȩ���ж�
 if($_SESSION['group']!=1)
 {
	 go($rooturl,"��ȷ�����Թ���Ա��ݵ�½..",2);
 }


$tpl_file="../html/user_admin.htm";
$side_nav="<li><a href='".$rooturl."/admin/user.php'>�˺Ź���</a></li><li><a href='".$rooturl."/admin/master.php'>˶ʿ����</a></li><li><a href='".$rooturl."/admin/phd.php'>��ʿ����</a></li>";

if(empty($_GET)) //default
{
	$re=$DB->query("select * from ".$db_prefix."User where UserGroup>1 order by UserGroup");
	
	$i=0;
	$user="<table align='center' width='100%'> 
	          <tr ><td></td> <td></td> <td align='right'> <a href='user.php?action=add'>������¼�˺�</a></td> </tr>
	          <tr> <td > <B>�˺�</B> </td> <td > <B>���</B> </td><td > <B>����</B> </td> </tr>";
    while($row=$DB->fetch_array($re))
    {
	   $i++;
	   if($row['UserGroup']==3)
	   {
		   $user_group="ѧ���û�";
	   }

	   $user.="<tr><td>  ".$row['Name']." &nbsp;  &nbsp; ".$row['Email']." </td> <td> ".$user_group." </td> <td> <a href='user.php?action=edit&Id=".$row['ID']."'>�༭</a> <a href='user.php?action=del&Id=".$row['ID']."' onclick='javascript:return confirm(\"ȷ��ɾ��?\");'>ɾ��</a> </td> </tr>";
	  
     }  
    $user.="</table>";
	
	$tpl_var=array("content_right"=>"user","side_nav"=>"side_nav");
	do_template($tpl_file,$tpl_var);
}


if($_GET['action']=="add")
{

  if($_POST['act']!="add")
  {
    $user="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'> </td></tr>

		  <tr> 
		       <td> ���� </td> 
		       <td > <INPUT TYPE=text' NAME='email' size=20> </td> 
	      </tr> 

          <tr>
		       <td width='80px'> ���� </td> 
			   <td > <INPUT TYPE=text' NAME='name' size=12> </td> 
		  </tr> 
		
		  <tr>
		         <td> ����</td> 
				 <td> <INPUT TYPE=text' NAME='password' size=12 value='123456'> </td> 
		  </tr>
         
		  <tr>
		       <td >���</td> 
			   <td > <SELECT NAME='group'><OPTION VALUE='3' SELECTED >ѧ���û�</OPTION><OPTION VALUE='2'>����Ա</OPTION></SELECT> </td> 
		  </tr> 
		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='����û�'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='����' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='add'> </tr>
		  </table></form>";
		 
    
    $tpl_var=array("content_right"=>"user","side_nav"=>"side_nav");
    do_template($tpl_file,$tpl_var);
  }

  else  //������
  {
	  $email=htmlspecialchars(trim($_POST['email']));
	  $name=htmlspecialchars(trim($_POST['name']));
	  $password=md5(trim($_POST['password']));
	  $user_group=intval($_POST['group']);

	  $DB->query("insert into ".$db_prefix."User(Name,Email,Password,UserGroup) values('".$name."','".$email."','".$password."','".$user_group."')" );

	  go("user.php","�����ɹ�");
  }
}

if($_GET['action']=="edit")
{


  if($_POST['act']!="edit")
   {

	  $id=intval($_GET['Id']);

      $row=$DB->fetch_one_array("select * from ".$db_prefix."User where ID=".$id);

	  $user="<form method='post'><table align='center' width='85%' cellpadding='10px'>
          <tr><td colspan=2 align='center'> </td></tr>

		  <tr> 
		       <td> ���� </td> 
		       <td > <INPUT TYPE=text' NAME='email' size=20 value='".$row['Email']."'> </td> 
	      </tr> 

          <tr>
		       <td width='80px'> ���� </td> 
			   <td > <INPUT TYPE=text' NAME='name' size=12 value='".$row['Name']."'> </td> 
		  </tr> 
		  
		  <tr>
		       <td >���</td> 
			   <td > <SELECT NAME='group'><OPTION VALUE='3' SELECTED >ѧ���û�</OPTION><OPTION VALUE='2'>����Ա</OPTION></SELECT> </td> 
		  </tr> 
		  <tr><td colspan=2 align='center'>   <br>  <INPUT TYPE='submit' value='�����û�'> &nbsp; &nbsp;  <INPUT TYPE='submit' value='����' onclick='javascript:history.go(-1); return false;'> </td> <INPUT TYPE='hidden' NAME='act' value='edit'> </tr>
		  </table></form>";
		 
    
    $tpl_var=array("content_right"=>"user","side_nav"=>"side_nav");
    do_template($tpl_file,$tpl_var);
   }

   else{

	  $email=htmlspecialchars(trim($_POST['email']));
	  $name=htmlspecialchars(trim($_POST['name']));
	  $user_group=intval($_POST['group']);

	  $id=intval($_GET['Id']);
      $DB->query("update ".$db_prefix."User set Name='".$name."',Email='".$email."',UserGroup='".$user_group."' where ID=".$id);
      go("user.php","�������");
   }

}
?>