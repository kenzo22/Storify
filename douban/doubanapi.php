<?php
require_once '../oauth.php';
require_once 'doubanoauth.php';
/** 
 * 豆瓣操作类 
 * 
 * 
 */ 
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