<?php
$html_title = "加标签";
require $_SERVER['DOCUMENT_ROOT']."/global.php";
require  $_SERVER['DOCUMENT_ROOT']."/include/header.php";

try{
    $dbh = new PDO("mysql:host=127.0.0.1;dbname=storybing",'root','kenzo22',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
/*
    $smst=$dbh->prepare('SELECT id FROM story_posts order by id asc');
    $smst->execute();
    $results=$smst->fetchAll();
    $pre_query1="insert into story_category set story_id=:story_id";
    $sth=$dbh->prepare($pre_query1);
    foreach($results as $row){
        $sth->execute(array(':story_id'=> $row['id']));
    }
*/
}
catch(PDOException $e){
    echo $e->getMessage();
}
if(isset($_GET['edit'])){
    $cmd="UPDATE story_category SET name=:name, subname=:subname WHERE story_id=:post_id";
    try{
        $st=$dbh->prepare($cmd);
    }
    catch(PDOException $e){
        $e->getMessage();
    }
    for($i=0;$i<sizeof($_POST['pname']);$i++){
        if($_POST['pname'][$i]=='')
            continue;
        try{
            $st->execute(array(':name'=>$_POST['pname'][$i],':subname'=>$_POST['sname'][$i],':post_id'=>$_POST['id'][$i]));
        }
        catch(PDOException $e){
            $e->getMessage();
        }
    }
}
?>


<div class='inner'>
  <form id='tag_form' action="/taglist.php?edit" method="post">
	<div class='title'> 未处理标签的故事 </div>  
    <table border="0">
    <?php
    $content="";
    $query="SELECT story_posts.id, post_author, post_title FROM story_posts, story_category WHERE story_posts.id=story_id AND (name ='' OR subname = '') ";
    $smst=$dbh->query($query);
    foreach($smst as $row){
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
