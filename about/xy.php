<?php
  
  include "../header.php";
  $side_nav="<li><a href='".$rooturl."/about/?advisor'>导师简介</a></li>          
			 <li><a href='".$rooturl."/login/'>用户登录</a></li>
			 <li><a href='".$rooturl."/about/?recruit'>报考招生</a></li>	
			 ";

if(isset($_GET['advisor']))
{ 
	$title="导师简介";
	$content_right="<div ><BR><BR><img src='".$rooturl."/img/advisor.jpg' style='float:right;width:150px;'>
	                 <span class='comman_text'>
	                 <p>李光耀：博士、研究员、博士生导师，同济大学CAD研究中心副主任。</p>
	                 <p>上海市高新技术转化专家组成员、上海市重大项目评审专家、上海电气—同济设计研发中心秘书长。</p>
					 <p>主要研究方向：计算机辅助设计分析与仿真；城市仿真与城市规划设计；三维数字沙盘与GIS系统研究；图形图象技术。</p>
					 <p>近五年来，先后承担国家自然科学基金项目、上海市政府咨询项目、上海浦东新区政府咨询项目、上海市经委重点项目、上海市科委项目、上海电气集团项目、上海轻工集团项目、上海市重大项目、与地方政府合作项目以及企业横向联合项目40余项；发表论文30余篇；出版教材20余本.
                     </p>
                     </span><BR><BR></div>";
}

if(isset($_GET['contact']))
{
   $title="联系我们";
   $content_right="<div><BR><BR>
                     <span class='comman_text'>
                     <p>同济大学嘉定校区电信楼494 </p>
					 <p>电 话:021-69587942</p>
					 <p>邮 箱:lgy@mail.tongji.edu.cn </p>

					 </span><BR><BR></div>";
}

if(isset($_GET['recruit']))
{
   $title="欢迎报考";
   $content_right="<div><BR><BR>
                     <span class='comman_text'>
   <p><B>硕士研究生</B> <BR></p> 
   <p>导师: 李光耀 </p>
   <p>专业代码:  081203 </p> 
   <p>专业名称:  计算机应用技术</p>   
   <p>研究方向:  CAD及企业信息化 </p> 

   
   <p>考试科目-1:  ①101 政治理论   ②201英语   ③301 数学一 ④408 计算机学科专业基础综合  </p>
   <p> 招生院系代码:  080  </p>
   <p> 招生院系名称:  电子与信息工程学院  </p>
   <p>复试科目名称:  综合能力测试  </p>
   <p>复试科目参考书目:  《微型计算机系统原理及应用》（上册），周明德，清华大学出版社，第三版；《数据库概论》，萨师煊，高等教育出版社；《UNIX操作系统》，尤晋元，西安电子科技大学出版社；《编译原理》，陈火旺，国防科技出版社  
   </p>
					 </span><BR><BR></div>";
}



$tpl_file="../html/about.htm";
$tpl_var=array("title"=>"title","content_right"=>"content_right","side_nav"=>"side_nav");
do_template($tpl_file,$tpl_var);
?>