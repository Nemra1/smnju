<script type="text/javascript" src="templates/swf/swfobject.js"></script>
<script type="text/javascript">
	// For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
	var swfVersionStr = "10.2.0";
	// To use express install, set to playerProductInstall.swf, otherwise the empty string. 
	var xiSwfUrlStr = "playerProductInstall.swf";
	var flashvars = {};
	var params = {};
	params.quality = "high";
	params.bgcolor = "#ffffff";
	params.allowscriptaccess = "sameDomain";
	params.allowfullscreen = "true";
	var attributes = {};
	attributes.id = "PicWall";
	attributes.name = "PicWall";
	attributes.align = "middle";
	swfobject.embedSWF(
	    site_url+"/templates/swf/picWall/PicWall.swf?path=%2Fflash%2Fsrc.php", "flashContent", 
	    "600", "400", 
	    swfVersionStr, xiSwfUrlStr, 
	    flashvars, params, attributes);
	// JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
	swfobject.createCSS("#flashContent", "display:block;text-align:left;");
</script>

    <div class="widget_content">
        <div class="content_center">
        
			<div id="flashContent">
				<p>
					To view this page ensure that Adobe Flash Player version 
					10.2.0 or greater is installed. 
				</p>
				<script type="text/javascript"> 
					var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://"); 
					document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
					+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
				</script> 
			</div>
        
			<noscript>
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="600" height="400" id="PicWall">
					<param name="movie" value="{$siteurl}/templates/swf/picWall/PicWall.swf?path=%2Fflash%2Fsrc.php" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="allowFullScreen" value="true" />
					<!--[if !IE]>-->
					<object type="application/x-shockwave-flash" data="{$siteurl}/templates/swf/picWall/PicWall.swf?path=%2Fflash%2Fsrc.php" width="600" height="400">
						<param name="quality" value="high" />
						<param name="bgcolor" value="#ffffff" />
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="allowFullScreen" value="true" />
					<!--<![endif]-->
					<!--[if gte IE 6]>-->
						<p> 
							Either scripts and active content are not permitted to run or Adobe Flash Player version
							10.2.0 or greater is not installed.
						</p>
					<!--<![endif]-->
						<a href="http://www.adobe.com/go/getflashplayer">
							<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
						</a>
					<!--[if !IE]>-->
					</object>
					<!--<![endif]-->
				</object>
			</noscript>
        
        </div>
    </div>
