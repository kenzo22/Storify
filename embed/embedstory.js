;
(function() {

	if (typeof Koulifang == 'object' && Koulifang.widget) {
		//debug('Koulifang initializer already started', Koulifang.widget.stories);
		getStories();
		return;
	}

  /* Koulifang Widget */
	var jQuery;

  if(typeof Koulifang!='object') {
    Koulifang = {};
  }

	Koulifang.widget = {
		stories: []
	};

	Koulifang.ready = false;
	Koulifang.permalinks = new Array;

	DEBUG = (typeof DEBUG == 'undefined') ? true : DEBUG;
	ENV = (typeof ENV == 'undefined') ? 'production' : ENV;

/******** Load jQuery if not present *********/
	if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.4.2') {
		var script_tag = document.createElement('script');
		script_tag.setAttribute("type", "text/javascript");
		script_tag.setAttribute("src", "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
		script_tag.onload = scriptLoadHandler;
		script_tag.onreadystatechange = function() { // Same thing but for IE
			if (this.readyState == 'complete' || this.readyState == 'loaded') {
				scriptLoadHandler();
			}
		};
		// Try to find the head, otherwise default to the documentElement
		(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
	} else {
		// The jQuery version on the window is the one we want to use
		KOULIFANG_JQUERY = window.jQuery;
		jQuery = KOULIFANG_JQUERY;
		main();
	}
	
	/******** Called once jQuery has loaded ******/
	function scriptLoadHandler() {
		// Restore $ and window.jQuery to their previous values and store the
		// new jQuery in our local jQuery variable
		jQuery = window.jQuery.noConflict(true);
		KOULIFANG_JQUERY = jQuery;
		// Call our main function
		main();
	}
	
	function require(jsfile, callback) {
		var script_tag = document.createElement('script');
		script_tag.setAttribute("type", "text/javascript");
		script_tag.setAttribute("src", jsfile);
		if (typeof callback == 'function') {
			script_tag.onload = callback;
			script_tag.onreadystatechange = function() { // IE
				if (this.readyState == 'complete' || this.readyState == 'loaded') {
					callback();
				}
			};
		} (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
	}
	
	function setenv()
	{
	  KOULIFANG_BASE_URL = 'http://koulifang.com';
	}
	
	function debug(msg, obj) {
    if(window.location.href.indexOf("DEBUG") == -1 && !window.location.href.match(/localhost.koulifang.com/)) return false;
		if (!obj) obj = '';
		if (window.console && console && console.log) console.log('koulifangWidget> ' + msg, obj);
	}
	
	/******** Our main function ********/
	function main() {
		var jQuery = KOULIFANG_JQUERY;
		//debug('Using jQuery ' + jQuery.fn.jquery);

		setenv();

		//jQuery('.stfyhtml').hide();
		//jQuery('.storify_html').hide(); // hiding the html export to wordpress
		jQuery('head').append('<link rel="stylesheet" href="' + KOULIFANG_BASE_URL + '/css/layout.css' + '" type="text/css" />');
		Koulifang.ready = true;
		getStories();
		/*require(KOULIFANG_BASE_URL + '/js/utils.js', function() {
			require(KOULIFANG_BASE_URL + '/js/ElementsList.js', function() {
				Koulifang.ready = true;
				getStories();
			});
		});*/
	}
	
	function queryStringToObject(queryString)
	{
	  if(!queryString) return {};
      if(queryString.indexOf('=')===-1) return {};
    
      var paramsArray = queryString.split('&');
      var params = {};
      for (var i=0, len=paramsArray.length; i < len; i++) {
        var p = paramsArray[i];
        var r = p.split('=');
        if(r.length>1)
        params[r[0]] = r[1];
      };
      return params;
	}
	
	function getStories() {
		if (!Koulifang.ready) return;
		var permalink, query, params, scriptsrc;

		jQuery = jQuery || KOULIFANG_JQUERY;
		jQuery('script[src*="' + KOULIFANG_BASE_URL + '/user"]').each(function() {
		  var scriptsrc = jQuery(this).attr('src');
			if (scriptsrc.indexOf('/public/') == -1 && jQuery.inArray(scriptsrc, Koulifang.permalinks) == -1) {
				permalink = scriptsrc.substr(0, scriptsrc.lastIndexOf('.'));
				query = scriptsrc.substr(scriptsrc.lastIndexOf('?')+1);
				if(query) {
				  params = queryStringToObject(query);
				}
				var slug = permalink.substr(permalink.lastIndexOf('/') + 1);
				if (typeof Koulifang.widget.stories[slug] == 'undefined') {
					Koulifang.permalinks.push(permalink);
					Koulifang.widget.stories[slug] = new Koulifang.Story(permalink, params);
				}
			}
		});
		/*debugger;
		permalink = "http://koulifang.com/22/story";
		params = {};
		var slug = "story";
		Koulifang.permalinks.push(permalink);
		Koulifang.widget.stories[slug] = new Koulifang.Story(permalink, params);*/
	}
	
	Koulifang.Story = function(permalink, options) {
	    this.permalink = permalink;
	    this.slug = permalink.substr(permalink.lastIndexOf('/') + 1);
        this.nodeId = 'sfywdgt_StorifyWidget_' + this.slug.replace(/_|\-/g,'');
	    var self = this,
			story = {},
			html = '',
			elements;
		var getData = {link: permalink};
		
		jQuery.ajax({
          url: 'http://koulifang.com/member/fetchstory.php',
		  data: getData, 
          success: function(data) {
            this.widgetNode = jQuery(data);
			jQuery('script[src^="' + self.permalink + '"]').first().after(this.widgetNode);
			jQuery('#embed_a').toggle(function(e){
				  e.preventDefault();
				  jQuery('#embed_bar').slideDown("slow");
				  jQuery('#embed_bar span .sto_embed').select();
				  return false;
				},
				function(e){
				  e.preventDefault();
				  jQuery('#embed_bar').slideUp("slow");
				  jQuery('#embed_bar span .sto_embed').select();
				  return false;
				});
          },
          scriptCharset: "utf-8",
          type: "GET"
        });	
	}
	
})();