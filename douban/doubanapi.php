<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/oauth.php';
require_once 'doubanoauth.php';
/** 
 * 豆瓣操作类 
 * 
 * 
 */  
function getPubDate($attArray) 
{
  $arrayLength = count($attArray);
  for($i=0;$i<$arrayLength; $i++)
  {
	if($attArray[$i]['@name'] == 'pubdate')
	break;
  }
  if($i == $arrayLength)
  {
    return "";
  }
  else
  {
    return $attArray[$i]['$t'];
  }
}
 
function getAuthors($doubanArray) 
{
  $author_count = count($doubanArray);
  $author="";
  if($author_count == 1)
  {
    $author = $doubanArray[0]['name']['$t'];
  }
  else if($author_count > 1)
  {
    for($i=0; $i<$author_count; $i++)
    {
	  $author .= $doubanArray[$i]['name']['$t']." ";
    }
  }
  return $author;
}
 
function getAuthorLink($authorArray)
{
  $arrayLength = count($authorArray);
  for($i=0; $i<$arrayLength; $i++)
  {
    if($authorArray[$i]['@rel'] == 'alternate')
    {
	  break;
    }
  }
  if($i == $arrayLength)
  {
    return "";
  }
  else
  {
    return $authorArray[$i]['@href'];
  }
}

function getAuthorPic($authorArray)
{
  $arrayLength = count($authorArray);
  for($i=0; $i<$arrayLength; $i++)
  {
    if($authorArray[$i]['@rel'] == 'icon')
    {
	  break;
    }
  }
  if($i == $arrayLength)
  {
    return "../img/douban_user_dft.jpg";
  }
  else
  {
    return $authorArray[$i]['@href'];
  }
}

function getItemLink($itemArray)
{
  $arrayLength = count($itemArray);
  for($i=0; $i<$arrayLength; $i++)
  {
    if($itemArray[$i]['@rel'] == 'alternate')
    {
	  break;
    }
  }
  if($i == $arrayLength)
  {
    return "";
  }
  else
  {
    return $itemArray[$i]['@href'];
  }
}

function getItemPic($itemArray)
{
  $arrayLength = count($itemArray);
  for($i=0; $i<$arrayLength; $i++)
  {
    if($itemArray[$i]['@rel'] == 'image')
    {
	  break;
    }
  }
  if($i == $arrayLength)
  {
    return "../img/event_dft.jpg";
  }
  else
  {
    return $itemArray[$i]['@href'];
  }
}
 
 
class DoubanClient 
{ 
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    { 
        $this->oauth = new DoubanOAuth( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    }
	
	function verify_credentials()
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/people/%40me' , $param); 
	}
	
	function get_user()
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/people/ahbei' , $param); 
	}
	
	function get_comment($commentID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/review/'.$commentID , $param); 
	}
	
	function get_book($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/book/subject/'.$subjectID , $param); 
	}
	
	function get_movie($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/movie/subject/'.$subjectID , $param);  
	}
	
	function get_music($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/music/subject/'.$subjectID , $param); 
	}
	
	function get_event($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/event/'.$subjectID , $param); 
	}
	
	function search_book($keywords, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/book/subjects' , $param); 
	}
	
	function search_movie($keywords, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/movie/subjects' , $param); 
	}
	
	function search_music($keywords, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/music/subjects' , $param); 
	}
	
	function search_event($keywords, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['location'] = 'all';
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/events' , $param); 
	}
	
	function search_book_reviews($subjectID, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/book/subject/'.$subjectID.'/reviews' , $param); 
	}
	
	function search_movie_reviews($subjectID, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/movie/subject/'.$subjectID.'/reviews' , $param); 
	}
	
	function search_music_reviews($subjectID, $startIndex, $numResults)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['start-index'] = $startIndex;
	  $param['max-results'] = $numResults;
	  return $this->oauth->get('http://api.douban.com/music/subject/'.$subjectID.'/reviews' , $param); 
	}
} 

?>
