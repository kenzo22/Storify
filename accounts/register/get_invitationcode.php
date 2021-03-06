<?php
   $html_title = "获得邀请码 - 口立方";
  include $_SERVER['DOCUMENT_ROOT']."/global.php";
  include $_SERVER['DOCUMENT_ROOT']."/include/mail_functions.php";
  
  $icode_len=6;
  $icode_table=$db_prefix."icode";
  $icode_max=4;
  
  $email=$_POST['email'];
  //Everytime a user used a invitation code to rigister, we should update the count of invitation code, this logic will be implemented in register_new.php
  //query the database and return the least used invitation code(The invite code which has she mimimal count)

  function get_invite_code(){	
	try{
		global $DB,$icode_len,$icode_table,$icode_max;
		
		$query="show tables";
		$results=$DB->query($query);
		$tables = array();
		while($showtablerow=$DB->fetch_array($results))
		{
			$tables[] = $showtablerow[0];
		}	
		if(!in_array($icode_table,$tables))
		{
			$query=	"create table ".$icode_table.       
				" (	ic_index int unsigned not null auto_increment primary key,
					ic_code char(".$icode_len.") not null,
					ic_email varchar(50),
					ic_time int unsigned not null
				);";
			$DB->query($query);
		}
			
		$results=$DB->query("select * from ".$icode_table);
		$num=$DB->num_rows($results);
		// Generate the random sting and insert into the table, the upper limit is $icode_max	
		for($i=0;$i < $icode_max - $num; $i++)
		{
			$string=produce_random_strdig($icode_len);
			// skip the same invite code
			$results=$DB->query("select * from ".$icode_table." where ic_code='".$string."'");
			if($DB->num_rows($results) != 0)
				continue;
			$query="insert into ".$icode_table." (ic_code) values ('".
					$string."')";
			if(!$DB->query($query))
				echo "insert invitation code failed<br>";
		}
		$query="select ic_code from ".$icode_table." where ic_email is null";
		$result=$DB->query($query);
		$row=$DB->fetch_array($result);
		return $row[0];
	}
	catch(Exception $e){
		echo $e->getMeesage();
		exit;
	}
  }
  
  try{
        if(! $email ){
                echo "请输入邮箱地址。<br>";
                return;
        }
	$res=get_invite_code();
	
	if(check_email('email',$email,'story_user')){
		echo "您已经注册了。.<br>";
		return;
	}
	if(check_email('ic_email',$email,$icode_table)){
		echo "您已经被邀请了，请查收邮件。<br>";
		return;
	}
	
	$time=time();
	$query="update ".$icode_table." set ic_email='".$email."', ic_time=".$time." where ic_code='".$res."'";
	$DB->query($query);
	
	$subject="口立方注册邀请码";
	$message="<p>您已经被口立方邀请。下面是您的注册邀请码:<br/><br/>".
		$res."<br/><br/>

		感谢您对口立方的支持，希望您在口立方的体验有益和愉快。<br/><br/>

		口立方 http://www.koulifang.com<br/><br/>

		(这是一封自动产生的email，请勿回复。)</p>";
		
	if(sendEmail($email,$subject,$message))
	{
		$content="<div class='div_center' > <span class='title'> 获取邀请码 </span></div> 
		<div class='div_center'><span>请到 ".$email." 查阅来自口立方的邮件, 从邮件获取您的邀请码。<span></div>
		<div class='div_center'><a target='_blank' href='http://mail.google.com'><span>登录Gmail邮箱查收邀请码邮件</span></a> </div>";
		echo $content;	
	}
  }
  catch(Exception $e){
	echo $e->getMessage();
	exit;
  }
?>
