<?php
 
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang/en.php";

if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
	include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang/".$lang.".php";
}

foreach ($bb_language as $i => $l) {
	$bb_language[$i] = str_replace("'", "\'", $l);
}

?>



(function($){   
		  
			$.bb = (function () {

				var currentChatboxId = 0;
				var onlineScroll;
				var chatScroll;
				var hideWebBar;
				var keyboardOpen = 0;
				var landscapeMode = 0;
				var buddyListName = {};
				var buddyListAvatar = {};
				var buddyListMessages = {};
				var longNameLength = 20;

				return {

					playSound: function() {

					},

					detect: function(keyboard) {

						baseWidth = screen.width;
						baseHeight = screen.height-51;
						sBaseHeight = screen.height-91;

						$("body").css('width',baseWidth+'px');
						$(".header").css('width',(baseWidth-14)+'px');
						$(".roundedtitle").css('width',(baseWidth-30)+'px');
						$(".footer").css('width',(baseWidth-14)+'px');
						$(".footer input").css('width',(baseWidth-32)+'px');
						$(".roundedcenter").css('width',(baseWidth-70)+'px');
						$("#cwwrapper").css('height',sBaseHeight+'px');

						$('body').css('display','block');

					},

					init: function() {

						jqcc.bb.detect();

						window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', function() {
									jqcc.bb.detect();
						}, false);

						document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

						$('#header .roundedright').click(function() {
							location.href = jqcc.cometchat.getBaseUrl()+'../';
						});
						
						jqcc.bb.hideBar();
					

					},

					hideBar: function() {
						if (landscapeMode == 1) {
							$('#chatmessage').blur();
							
						} else {
							
						}
						
						clearTimeout(hideWebBar);
						hideWebBar = setTimeout(function(){jqcc.bb.hideBar()}, 1000);
					},

					updateBuddyList: function(data) {

						var buddylist = '';
						
						$.each(data, function(i,buddy) {
								 
							if (buddy.n.length > longNameLength) {
								longname = buddy.n.substr(0,longNameLength)+'...';
							} else {
								longname = buddy.n;
							}

							buddyListName[buddy.id] = buddy.n;
							buddyListAvatar[buddy.id] = buddy.a;

							if (!buddyListMessages[buddy.id]) {
								buddyListMessages[buddy.id] = 0;
							}

							buddylist += '<li class="onlinelist" id="onlinelist_'+buddy.id+'" onclick="javascript:jqcc.bb.loadPanel(\''+buddy.id+'\')"><img src="'+buddy.a+'" class="avatarimage">'+longname+'<div class="status">'+buddy.s+'</div><div class="newmessages"></div></li>';

							$('#onlinelist_'+buddy.id).remove();
						});

						if (buddylist == '') {
							buddylist += '<li class="onlinelist" id="nousersonline">'+jqcc.cometchat.getLanguage(14)+'</li>';
						}

						$('#wolist').html(buddylist);
					},

					loggedOut: function() {
						alert('<?php echo $bb_language[5];?>');
						location.href = jqcc.cometchat.getBaseUrl()+'../';
					},

					sendMessage: function(id) {
						var message = $('#chatmessage').val();
						$('#chatmessage').val('');
						jqcc.cometchat.sendMessage(id,message);
						$('#chatmessage').focus();

						fromname = '<?php echo $bb_language[6];?>';
						selfstyle = 'selfmessage';

						var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

						var temp = (('<li><div class="cometchat_chatboxmessage '+selfstyle+'" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong><?php echo $bb_language[7];?></span><span class="cometchat_chatboxmessagecontent">'+message+'</span>'+'</div></li>'));

						if (currentChatboxId == id) {
							$('#cwlist').append(temp);

							setTimeout(function () {var o = document.getElementById("cwwrapper");o.scrollTop = o.scrollHeight;}, 200);
							
						}

						return false;
					},

					newMessage: function(incoming) {

						if (!buddyListName[incoming.from]) {		
							jqcc.cometchat.getUserDetails(incoming.from);
						}

						fromname = buddyListName[incoming.from];

						if (fromname.indexOf(" ") != -1) {
							fromname = fromname.slice(0,fromname.indexOf(" "));
						}

						var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

						var atleastOneNewMessage = 0;

						if (incoming.self == 0) {
							var temp = (('<li><div class="cometchat_chatboxmessage" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong><?php echo $bb_language[7];?></span><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+'</div>'));
							atleastOneNewMessage++;
						}

						if (currentChatboxId == incoming.from) {
							$('#cwlist').append(temp);

							var o = document.getElementById("cwwrapper");o.scrollTop = o.scrollHeight;
									
						} else {
							if (buddyListMessages[incoming.from]) {
								buddyListMessages[incoming.from] += 1;
							} else {
								buddyListMessages[incoming.from] = 1;
							}

							$('#onlinelist_'+incoming.from+' .newmessages').html(buddyListMessages[incoming.from]);
						}

						if (atleastOneNewMessage) {
							jqcc.bb.playSound();
						}

					},

					loadUserData: function(id,data) {
						buddyListName[id] = data.n;
						buddyListAvatar[id] = data.a;
					
						if (!buddyListMessages[id]) {
							buddyListMessages[id] = 0;
						}

						if (data.n.length > longNameLength) {
							longname = data.n.substr(0,longNameLength)+'...';
						} else {
							longname = data.n;
						}

						var buddylist = '<li class="onlinelist" id="onlinelist_'+data.id+'" onclick="javascript:jqcc.bb.loadPanel(\''+data.id+'\')"><img src="'+data.a+'" class="avatarimage">'+longname+'<div class="status">'+data.s+'</div><div class="newmessages"></div></li>';

						$('#nousersonline').css('display','none');
						$('#permanent').prepend(buddylist);
					},

					newMessages: function(data) {

						var temp = '';
						var atleastOneNewMessage = 0;						

						$.each(data, function(i,incoming) {
									
								if (!buddyListName[incoming.from]) {
									jqcc.cometchat.getUserDetails(incoming.from);
								}


								fromname = buddyListName[incoming.from];

								if (fromname.indexOf(" ") != -1) {
									fromname = fromname.slice(0,fromname.indexOf(" "));
								}

								var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

								if (incoming.self == 0) {
									var temp = (('<li><div class="cometchat_chatboxmessage" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong><?php echo $bb_language[7];?></span><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+'</div>'));
									atleastOneNewMessage++;

									if (currentChatboxId == incoming.from) {
										$('#cwlist').append(temp);
										
										var o = document.getElementById("cwwrapper");
										o.scrollTop = o.scrollHeight;

									} else {
										if (buddyListMessages[incoming.from]) {
											buddyListMessages[incoming.from] += 1;
										} else {
											buddyListMessages[incoming.from] = 1;
										}


										$('#onlinelist_'+incoming.from+' .newmessages').html(buddyListMessages[incoming.from]);
									}

								}


						});
						
						if (atleastOneNewMessage) {
							jqcc.bb.playSound();
						}
					},

					loadPanel: function (id,name) {

						buddyListMessages[id] = 0;
						$('#onlinelist_'+id+' .newmessages').html('');

						$('#chatwindow').html('<div id="cwheader" class="header"><div class="roundedleft">&nbsp;</div><div class="roundedcenter">'+buddyListName[id]+'</div><div class="roundedright"><?php echo $bb_language[8];?></div><div style="clear:both"></div></div><div id="cwwrapper"><div id="cwscroller"><ul id="cwlist"></ul></div></div><div id="cwfooter" class="footer"><form onsubmit="return jqcc.bb.sendMessage(\''+id+'\')"><input type="text" name="chatmessage" placeholder="<?php echo $bb_language[9];?>" id="chatmessage" /></div>');

						$('#whosonline').css('display','none');
						$('#chatwindow').css('display','block');

						setTimeout(function(){}, 1000);
						
						jqcc.bb.detect();

						currentChatboxId = id;

						$('#chatwindow .roundedright').click(function() {
							jqcc.bb.back();
						});

						$('#chatmessage').blur(function() {
							keyboardOpen = 0;
							jqcc.bb.detect();
							setTimeout(function () { chatScroll.refresh() }, 0)
						});


						jqcc.cometchat.getRecentData(id);
					},

					loadData: function (id,data) {
						$.each(data, function(type,item){
								if (type == 'messages') {

									var temp = '';
									$.each(item, function(i,incoming) {
										
										var selfstyle = '';
										if (incoming.self == 1) {
											fromname = '<?php echo $bb_language[6];?>';
											selfstyle = 'selfmessage';
										} else {
											fromname = buddyListName[id];
										}

										var ts = new Date(incoming.sent * 1000);

										if (fromname.indexOf(" ") != -1) {
											fromname = fromname.slice(0,fromname.indexOf(" "));
										}
								
										temp += ('<li><div class="cometchat_chatboxmessage '+selfstyle+'" id="cometchat_message_'+incoming.id+'"><span class="cometchat_chatboxmessagefrom'+selfstyle+'"><strong>'+fromname+'</strong><?php echo $bb_language[7];?></span><span class="cometchat_chatboxmessagecontent'+selfstyle+'">'+incoming.message+'</span>'+'</div>');

									});

									if (currentChatboxId == id) {
										$('#cwlist').append(temp);
										setTimeout(function () {var o = document.getElementById("cwwrapper");o.scrollTop = o.scrollHeight;}, 200);			
									}
								}

							});
					},

					back: function() {
						$('#chatwindow').css('display','none');
						$('#chatwindow').html('');
						$('#whosonline').css('display','block');
						$('#onlinelist_'+currentChatboxId+' .newmessages').html('');
						currentChatboxId = 0;
					}

				};
			})();
		 
		})(jqcc);

		var listener = function (e) {
			e.preventDefault();
		};

window.onload = function() {
	jqcc.bb.init();
}