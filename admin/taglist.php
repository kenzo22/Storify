<?php
$html_title = "加标签";
require_once $_SERVER['DOCUMENT_ROOT']."/global.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/header.php";

$user_id=intval($_SESSION['uid']);
if($user_id == 1 || $user_id == 2){

try{
    $dbh = new PDO("mysql:host=127.0.0.1;dbname=storybing",'story_user','jin12web',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

    $smst=$dbh->prepare('SELECT id FROM story_posts order by id asc');
    $smst->execute();
    $results=$smst->fetchAll();
    $pre_query1="insert into story_category set story_id=:story_id on duplicate key update story_id=story_id";
    $sth=$dbh->prepare($pre_query1);
    foreach($results as $row){
        $sth->execute(array(':story_id'=> $row['id']));
    }
    
    if(isset($_GET['edit'])){
        $cmd="UPDATE story_category SET name=:name, subname=:subname WHERE story_id=:post_id";
        $st=$dbh->prepare($cmd);
        for($i=0;$i<sizeof($_POST['pname']);$i++){
            if($_POST['pname'][$i]=='')
                continue;
            $st->execute(array(':name'=>$_POST['pname'][$i],':subname'=>$_POST['sname'][$i],':post_id'=>$_POST['id'][$i]));
        }
    }
}
catch(PDOException $e){
    echo $e->getMessage();
}
?>

<div class='inner'>
  <form id='tag_form' action="/taglist" method="post">
	<div class='title'> 未处理标签的故事 </div>  
    <table border="0">
    <?php
    $content="<tr>
                <td>故事ID</td>
                <td>故事标题</td>
                <td>一级分类</td>
                <td>二级分类</td>
             </tr>";
    $query="SELECT story_posts.id, post_author, post_title FROM story_posts, story_category WHERE story_posts.id=story_id AND (name ='' OR subname = '') ";
    $smst=$dbh->prepare($query);
    $smst->execute();
    $results=$smst->fetchAll(PDO::FETCH_ASSOC);
    foreach($results as $row){
        $content.= "<tr>
                        <td><input type='text' name='id[]' value='".$row['id']."' readonly='readonly'/></td>
                        <td><a href=\"http://www.koulifang.com/user/".$row['post_author']."/".$row['id']."\">".$row['post_title']."</a></td>
                        <td><input type='text' name=\"pname[]\"/></td>
                        <td><input type='text' name=\"sname[]\"/></td>
                    </tr>";
    }
    echo $content;
    ?>
    </table>
    <div class='submit'><input type='submit' value='submit' /></div>
  </form>
</div>
    
</body>
</html>
<?php
}
    include $_SERVER['DOCUMENT_ROOT']."/include/footer.htm";
?>
