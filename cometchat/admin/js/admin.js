(function($){   
  
	$.cometchatspy = function(){

		var heartbeatTimer;
		var timeStamp = '0';		

		function chatHeartbeat(){	
			
			$.ajax({
				url: "index.php?module=spy&action=data",
				data: {timestamp: timeStamp},
				type: 'post',
				cache: false,
				dataFilter: function(data) {
					if (typeof (JSON) !== 'undefined' && typeof (JSON.parse) === 'function')
					  return JSON.parse(data);
					else
					  return eval('(' + data + ')');
				},
				success: function(data) {
					if (data) {
						var htmlappend = '';

						$.each(data, function(type,item){
							if (type == 'timestamp') {
								timeStamp = item;
							}

							if (type == 'online') {
								$('#online').html(item);
							}

							if (type == 'messages') {
								$.each(item, function(i,incoming) {
									htmlappend = '<div class="chat"><div class="chatrequest2">'+incoming.fromu+' -> '+incoming.tou+'</div><div class="chatmessage2" >'+incoming.message+'</div><div style="clear:both"></div></div>' + htmlappend;

								});
							}
						});

						if (htmlappend != '') {
							$("#data").prepend(htmlappend);
							$('div.message').fadeIn(2000);
							$('div.message:gt(19)').remove(); 
						}
					}
					
				clearTimeout(heartbeatTimer);
				heartbeatTimer = setTimeout( function() { chatHeartbeat(); },3000);
				
			}});

		}

		chatHeartbeat();

	} 
  
})(jQuery);


(function($){   
  
	$.fancyalert = function(message){
		if ($("#alert").length > 0) {
			removeElement("alert");
		}

		var html = '<div id="alert">'+message+'</div>';
		$('body').append(html);
		$alert = $('#alert');
			if($alert.length) {
				var alerttimer = window.setTimeout(function () {
					$alert.trigger('click');
				}, 5000);
				$alert.css('border-bottom','4px solid #76B6D2');
				$alert.animate({height: $alert.css('line-height') || '50px'}, 200)
				.click(function () {
					window.clearTimeout(alerttimer);
					$alert.animate({height: '0'}, 200);
					$alert.css('border-bottom','0px solid #333333');
				});
			}
	};   
  
})(jQuery);

/* Modules */

function modules_updateorder(del,ren,showhide) {
	order = [];
	$('#modules_livemodules').children('li').each(function(idx, elm) {
		order.push("\$trayicon[] = array('"+elm.id+"','"+$(elm).attr('d1')+"','"+$(elm).attr('d2')+"','"+$(elm).attr('d3')+"','"+$(elm).attr('d4')+"','"+$(elm).attr('d5')+"','"+$(elm).attr('d6')+"','"+$(elm).attr('d7')+"','"+$(elm).attr('d8')+"');")		 
	});  

	$.post('?module=modules&action=updateorder', {'order[]': order}, function(data) {
		if (showhide) {
			$.fancyalert('Module text will now be '+showhide+' in the bar');
		} else if (ren) {
			$.fancyalert('Module successfully renamed.');
		} else if (del) {
			$.fancyalert('Module successfully removed.');
		} else {
			$.fancyalert('Modules order successfully updated.');
		}
	});

}

function modules_removemodule(id) {
	var answer = confirm ('This action cannot be undone. Are you sure you want to perform this action?');
	if (answer) {
		removeElement(id);
		modules_updateorder(true);
	}
}

function modules_renamemodule(id) {
	if (document.getElementById(id+'_title').innerHTML.indexOf('<a href="?module=modules">cancel</a>') == -1) {
		document.getElementById(id+'_title').innerHTML = '<input type="textbox" id="'+id+'_newtitle" class="inputboxsmall" style="margin-bottom:3px" value="'+document.getElementById(id+'_title').innerHTML+'"/><br/><input type="button" onclick="javascript:modules_renamemoduleprocess(\''+id+'\');" value="Rename" class="buttonsmall">&nbsp;&nbsp;or <a href="?module=modules">cancel</a>';
	}
}

function modules_renamemoduleprocess(id) {
	var newtitle = document.getElementById(id+'_newtitle').value+'';
	newtitle = newtitle.replace(/"/g,'');

	document.getElementById(id).setAttribute('d1',newtitle.replace("'","\\\\\\\'"));
	document.getElementById(id+'_title').innerHTML = newtitle;
	modules_updateorder(false,true);
}

function modules_showtext(self,id) {
	var current = $('#'+id).attr('d8');

	if (current == '' || current == 0) {
		newvalue = 1;
		self.innerHTML = 'hide text';
	} else {
		newvalue = '';
		self.innerHTML = 'show text';
	}

	document.getElementById(id).setAttribute('d8',newvalue);
	if (newvalue == 1) { text = 'shown'; } else { text = 'hidden'; }
	modules_updateorder(false,false,text);
}

function removeElement(id) {
  var element = document.getElementById(id);
  element.parentNode.removeChild(element);
}


/* Plugins */

function plugins_updateorder(del) {
	order = '';
	$('#modules_liveplugins').children('li').each(function(idx, elm) {
		order += "'"+$(elm).attr('d1')+"',"; 
	});  

	$.post('?module=plugins&action=updateorder', {'order': order}, function(data) {
		if (del) {
			$.fancyalert('Plugin successfully removed.');
		} else {
			$.fancyalert('Plugins order successfully updated.');
		}
	});

}

function plugins_removeplugin(id) {
	var answer = confirm ('This action cannot be undone. Are you sure you want to perform this action?');
	if (answer) {
		removeElement(id);
		plugins_updateorder(true);
	}
}

function plugins_updatechatroomorder(del) {
	order = '';
	$('#modules_liveplugins').children('li').each(function(idx, elm) {
		order += "'"+$(elm).attr('d1')+"',"; 
	});  

	$.post('?module=plugins&action=updatechatroomorder', {'order': order}, function(data) {
		if (del) {
			$.fancyalert('Plugin successfully removed.');
		} else {
			$.fancyalert('Plugins order successfully updated.');
		}
	});

}

function plugins_removechatroomplugin(id) {
	var answer = confirm ('This action cannot be undone. Are you sure you want to perform this action?');
	if (answer) {
		removeElement(id);
		plugins_updatechatroomorder(true);
	}
}

function plugins_renameplugin(id) {
	$.fancyalert('Please edit the plugin language to modify the name');
}

function themes_makedefault(id) {
	$.post('?module=themes&action=makedefault', {'theme': id}, function(data) {
		location.href = '?module=themes';
	});
}

function themes_edittheme(id) {
	location.href = '?module=themes&action=edittheme&data='+id;
}

function themes_removetheme(id) {
	var answer = confirm ('This action cannot be undone. Are you sure you want to perform this action?');
	if (answer) {
		location.href = '?module=themes&action=removethemeprocess&data='+id;
	}
}

function logs_gotouser(id) {
	location.href = '?module=logs&action=viewuser&data='+id;
}

function logs_gotouserb(id,id2) {
	location.href = '?module=logs&action=viewuserconversation&data='+id+'&data2='+id2;
}

function modules_configmodule(id) {
	window.open('?module=dashboard&action=loadexternal&type=module&name='+id,'external','width=400,height=300,resizable=1,scrollbars=1');
}

function plugins_configplugin(id) {
	window.open('?module=dashboard&action=loadexternal&type=plugin&name='+id,'external','width=400,height=300,resizable=1,scrollbars=1');
}

function themes_updatecolors(theme) {
	var colors = {};
	$('.colorSelector').each(function() {
		colors[$(this).attr('oldcolor')] = $(this).attr('newcolor');
	})

	$.post('?module=themes&action=updatecolorsprocess', {'theme': theme, 'colors': colors}, function(data) {
		$.fancyalert('Theme colors have been successfully updated.');
	});
	return false;
}

function language_updatelanguage(md5,id,file,lang) {
	var language = {};
	$('#'+md5+' textarea').each(function(index,value) {
		language[index] = $(value).attr('value');	
	})
	$.post('?module=language&action=editlanguageprocess', {'id': id, 'lang': lang, 'file': file, 'language': language}, function(data) {
		$.fancyalert('Language has been successfully modified.');
	});
	return false;
}

function language_restorelanguage(md5,id,file,lang) {
	var language = {};
	$('#'+md5+' textarea').each(function(index,value) {
		language[index] = $(value).attr('value');	
	})
	$.post('?module=language&action=restorelanguageprocess', {'id': id, 'lang': lang, 'file': file, 'language': language}, function(data) {
		window.location.reload();
	});
	return false;
}

function themes_removelanguage(id) {
	var answer = confirm ('This action cannot be undone. Are you sure you want to perform this action?');
	if (answer) {
		location.href = '?module=language&action=removelanguageprocess&data='+id;
	}
}

function embed_link(url,width,height) {
	embedlink = window.open('','embedlink','width=400,height=100,resizable=0,scrollbars=0');
	embedlink.document.write("<title>Embed Link</title><style>textarea { border:1px solid #ccc; color: #333; font-family:verdana; font-size:12px; }</style>");
	embedlink.document.write('<textarea style="width:380px;height:80px"><iframe src="'+url+'" width="'+width+'" height="'+height+'" frameborder="1" ></iframe></textarea>');
	embedlink.document.close();
	embedlink.focus();
}