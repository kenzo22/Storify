<?php
$htm_title="主页推荐";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/header.php";

$user_id=intval($_SESSION['uid']);
if($user_id == 1 || $user_id == 2){

$category=array("社会","娱乐","科技","体育");
$max=4;
try{
    $dbh = new PDO("mysql:host=127.0.0.1;dbname=storybing",'root','kenzo22',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(isset($_GET['edit'])){
        $sh=$dbh->prepare("SELECT * FROM story_maintale WHERE category=:cate");
        for($j=0;$j<sizeof($_POST['list']);$j++){
            if(!$_POST['list'][$j])
                continue;
            $tmp_array=explode(",",$_POST['list'][$j]);
            if(sizeof($tmp_array)<$max){
                echo "For $category[$j], Set $max tales!";
                continue;
            }
            array_splice($tmp_array,4);
            $post_str=implode(",",$tmp_array);
            $sh->execute(array(':cate'=>$category[$j]));
            $row=$sh->fetch();
            if($row){
                $cmd="UPDATE story_maintale SET post_str=:str WHERE category=:cate";
            }else{
                $cmd="INSERT INTO story_maintale SET category=:cate, post_str=:str";
            }
            $sh->closeCursor();
            $stm=$dbh->prepare($cmd);
            $stm->execute(array(':cate'=>$category[$j],':str'=>$post_str));
       }
    }
}catch(PDOException $e){
    $e->getMessage();
}

?>

<div class='inner'>
  <form id='tale_form' action="/maintale" method="post">
	<div class='title'> 首页显示故事 </div>  
    <table border="0">
    <?php
    $content="<tr>
                <td>一级分类</td>
                <td>输入3个故事ID，用,分割</td>
             <tr/>";
    $sql="SELECT * FROM story_maintale WHERE category=:cate";
    $smth=$dbh->prepare($sql);
    foreach($category as $cat){
        $smth->execute(array(':cate'=>$cat));
        $row=$smth->fetch();
        $smth->closeCursor();
        $content.= "<tr>
                        <td>$cat</td>
                        <td><input type='text' name=\"list[]\" value='".$row['post_str']."'/></td>
                    </tr>";
    }
    echo $content;
    ?>
    </table>
    <div class='submit'><input type='submit' value='submit' /></div>
  </form>
</div>

<?php
}
include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
</body>
</html>
