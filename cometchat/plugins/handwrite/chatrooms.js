<?php
		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php";
		} else {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/en.php";
		}

		foreach ($handwrite_language as $i => $l) {
			$handwrite_language[$i] = str_replace("'", "\'", $l);
		}
?>

(function($){   
  
	$.cchandwrite = (function () {

		var title = '<?php echo $handwrite_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = getBaseUrl();
				window.open (baseUrl+'plugins/handwrite/index.php?chatroommode=1&id='+id, 'handwrite',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=330,height=250"); 
			}

        };
    })();
 
})(jqcc);