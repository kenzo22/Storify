<?php
  
  include "../header.php";
  $side_nav="<li><a href='".$rooturl."/about/?advisor'>��ʦ���</a></li>          
			 <li><a href='".$rooturl."/login/'>�û���¼</a></li>
			 <li><a href='".$rooturl."/about/?recruit'>��������</a></li>	
			 ";

if(isset($_GET['advisor']))
{ 
	$title="��ʦ���";
	$content_right="<div ><BR><BR><img src='".$rooturl."/img/advisor.jpg' style='float:right;width:150px;'>
	                 <span class='comman_text'>
	                 <p>���ҫ����ʿ���о�Ա����ʿ����ʦ��ͬ�ô�ѧCAD�о����ĸ����Ρ�</p>
	                 <p>�Ϻ��и��¼���ת��ר�����Ա���Ϻ����ش���Ŀ����ר�ҡ��Ϻ�������ͬ������з��������鳤��</p>
					 <p>��Ҫ�о����򣺼����������Ʒ�������棻���з�������й滮��ƣ���ά����ɳ����GISϵͳ�о���ͼ��ͼ������</p>
					 <p>�����������Ⱥ�е�������Ȼ��ѧ������Ŀ���Ϻ���������ѯ��Ŀ���Ϻ��ֶ�����������ѯ��Ŀ���Ϻ��о�ί�ص���Ŀ���Ϻ��п�ί��Ŀ���Ϻ�����������Ŀ���Ϻ��Ṥ������Ŀ���Ϻ����ش���Ŀ����ط�����������Ŀ�Լ���ҵ����������Ŀ40�����������30��ƪ������̲�20�౾.
                     </p>
                     </span><BR><BR></div>";
}

if(isset($_GET['contact']))
{
   $title="��ϵ����";
   $content_right="<div><BR><BR>
                     <span class='comman_text'>
                     <p>ͬ�ô�ѧ�ζ�У������¥494 </p>
					 <p>�� ��:021-69587942</p>
					 <p>�� ��:lgy@mail.tongji.edu.cn </p>

					 </span><BR><BR></div>";
}

if(isset($_GET['recruit']))
{
   $title="��ӭ����";
   $content_right="<div><BR><BR>
                     <span class='comman_text'>
   <p><B>˶ʿ�о���</B> <BR></p> 
   <p>��ʦ: ���ҫ </p>
   <p>רҵ����:  081203 </p> 
   <p>רҵ����:  �����Ӧ�ü���</p>   
   <p>�о�����:  CAD����ҵ��Ϣ�� </p> 

   
   <p>���Կ�Ŀ-1:  ��101 ��������   ��201Ӣ��   ��301 ��ѧһ ��408 �����ѧ��רҵ�����ۺ�  </p>
   <p> ����Ժϵ����:  080  </p>
   <p> ����Ժϵ����:  ��������Ϣ����ѧԺ  </p>
   <p>���Կ�Ŀ����:  �ۺ���������  </p>
   <p>���Կ�Ŀ�ο���Ŀ:  ��΢�ͼ����ϵͳԭ��Ӧ�á����ϲᣩ�������£��廪��ѧ�����磬�����棻�����ݿ���ۡ�����ʦ�ӣ��ߵȽ��������磻��UNIX����ϵͳ�����Ƚ�Ԫ���������ӿƼ���ѧ�����磻������ԭ�����»����������Ƽ�������  
   </p>
					 </span><BR><BR></div>";
}



$tpl_file="../html/about.htm";
$tpl_var=array("title"=>"title","content_right"=>"content_right","side_nav"=>"side_nav");
do_template($tpl_file,$tpl_var);
?>