<?php
include "../global.php";

$uid=intval($_SESSION['uid']);

if($_POST['act'] == 'uploadphoto')
{
        //权限判断
        if(!islogin())  
                go($rooturl."/login","请先登录..",2);

        $uid=intval($_SESSION['uid']);

        $err_code=$_FILESs['photofile']['error'];
        if($err_code != 0 ){
                echo 'Problems:';
                switch($err_code) 
                {
                        case 1: echo "File exceeded upload_max_filesize";
                                break;
                        case 2: echo "File exceeded max_file_size";
                                break;
                        case 3: echo "File only partially uploaded";
                                break;
                        case 4: echo "No File uploaded";
                                break;
                        case 6: echo "Cannot upload File: No temp directory specified";
                                break;
                        case 7: echo "Upload failed: Cannot write to disk";
                                break;
                }
                exit;
        }

        $original=htmlspecialchars(trim($_FILES['photofile']['name']));
        $type=$_FILES['photofile']['type'];
        $size=$_FILES['photofile']['size'];

        $upload_dir= "../img/user/"; 

        $ftype=explode("/",$type);

        if($ftype[0] != "image"){
                echo "文件类型错误";
                exit;
        }

        if (is_uploaded_file($_FILES['photofile']['tmp_name']) ){
                $reslut=$DB->fetch_one_array("select Photo from ".$db_prefix."user where ID=".$uid);
                if(!empty($reslut['Photo']))
                        unlink($upload_dir.$reslut['Photo']);

                $filename=$uid.substr($original,-4,4);
                $local_file=$upload_dir.$filename;
                if(!move_uploaded_file($_FILES['photofile']['tmp_name'],$local_file)){
                        echo "无法将文件移到目的位置";
                }
                chmod($local_file,0755);
                $DB->query("update ".$db_prefix."user set Photo='".$filename."' where  ID=".$uid);
                go($rooturl."/member/user_setting.php","上传照片成功",2);
                
        }
        else
        {
                echo "可能出现文件上传攻击。文件名:";
                echo $_FILES['photofile']['name'];
                exit;
        }
}

if($_POST[act]!='modify')
{
        $result=$DB->fetch_one_array("select * from story_user where id=".$uid);

        if(!empty($result['photo']))
                $userphoto="<img width='270px' src='".$rooturl."/img/user/".$result['photo']."'> </img>";
        else
                $userphoto="照片预览";

        $user_set="<div class='div_center_870' >
                <table align='center' cellpadding='10px' >
                <tr><td colspan=3 align='center' ><BR><BR> <b>控制面板</b> <BR><BR></td></tr>
                <form name='form1'  method='post'  encType='multipart/form-data' target='hidden_frame' >
                <tr> 
                <td > 照片 <BR></td> 
                <td>
                        <input type='hidden' name='MAX_FILE_SIZE' value='1000000' />  
                        <input type='file' id='upfile' name='photofile' value='".$result['username']."' />
                        <input type='submit' value='上传照片' onclick='javascript:updatephoto();' />
                        <input type='hidden' name='act' value='uploadphoto' />
                </td> 
                </tr> 
                </form > 

                <form name='form2' method='post'>     
                <tr>
                <td width='100px'> 姓名 </td> 
                <td >".$result['username']."</td>  
                <td align='center' rowspan='3' id='photoimg'>".$userphoto." </td>  </tr>
                <tr> 
                <td > 邮箱 </td>   
                <td > ".$result['email']."</td>   
                </tr>
                <tr>  
                <td > 介绍下自己吧 <BR> (支持HTML) </td> 
                <td  colspan=2 ><textarea cols=80 rows=10 name='intro'>".$result['intro']."</textarea></td>  
                </tr>
                <tr> <td colspan=3 align='center'> <input type='submit' value='修改资料'> <input type='hidden' name='act' value='modify'></td> </tr>
                </form>

                </table></div>
                <iframe name='hidden_frame' id='hidden_frame' style='display:none'></iframe>";

        echo $user_set;
}
else
{
        $search = array ("'<script[^>]*?>.*?</script>'si","'<head[^>]*?>.*?</head>'si");

        $intro=addslashes(preg_replace($search,"",trim($_POST['intro'])));
        $mobile=htmlspecialchars(trim($_POST["mobile"]));
        $unit=htmlspecialchars(trim($_POST["unit"]));
        //echo $intro;
        $DB->query("update ".$db_prefix."user set intro='".$intro."' ,mobile='".$mobile."' ,unit='".$unit."'  where id=".$uid);
        go($rooturl."/member/user_setting.php","设置成功..",0);
}


include "../include/footer.htm"
?>

<script language="javascript">

function updatephoto() 
{
        var path=document.getElementById("upfile").value;

        document.getElementById("photoimg").innerHTML="<img width='270px' src='"+path+"'>";
}
</script>
