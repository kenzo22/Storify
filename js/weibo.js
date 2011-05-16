WB.core.load(['connect', 'client'], function() 
{
  var cfg = {
              key: '314237338',
			  xdpath: 'http://story.com/storify/html/xd.html'
			};
  WB.connect.init(cfg);
  WB.client.init(cfg);
});
			
function log(sData)
{
	$('#outputBox')[0].value = sData;
}
			
function weibo_login() 
{
  WB.connect.login(function() {
    self.location = '/storify/member/';
	log('login');
  });
}
			
function weibo_logout() 
{
  WB.connect.logout(function() {
	log('logout');
  });
}

function run_api_cmd()
{
  var method = '/search.json';
  var type = 'get';
  var args = {};
  args['q'] = 'dota';
  var loginfo;
  WB.client.parseCMD(method, function(sResult, bStatus)
  {
    $.each(sResult.results, function(i, result)
	{
	  $.each(result, function(key, value)
	  {
		loginfo += result[key] + '\n';
	  });
	});  
    log(loginfo);
  },
  args,
  {
    'method': type
  })
}