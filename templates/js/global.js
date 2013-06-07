//timeTolerant = 5000 //多久后报告网络问题
//存放已经初始化的ckeditor的id，避免多次初始化
ckeditorIdArray = new Array();
function replace_editor(id){
    if (ckeditorIdArray[id] == 1) {
        return;
    }
    else {
        ckeditorIdArray[id] = 1;
    }
    var smiles = new Array();
    for (var i = 1; i <= 37; i++) {
        smiles.push(i.toString() + '.gif');
    }
    return CKEDITOR.replace(id, {
        toolbar: [['Bold', 'Italic', 'Underline', 'Strike'], ['Font', 'FontSize'], ['TextColor', 'BGColor', 'Smiley', 'Image'], ['Link', 'Unlink']],
        width: 650,
        height: 300,
        resize_enabled: false,
        enterMode: 2,
        shiftEnterMode: 2,
        language: 'zh-cn',
        filebrowserUploadUrl: site_url + '/upload/editor_img/',
        fontSize_defaultLabel: '16'
    });
}

function process_form(opt){


    set_ckeditor_val();
    
    var options = {
        form: 'main_form',
        error: function(r){
            show_message_box(r['info']);
        },
        success: function(){
        },
        not_empty: [],
        validate: function(data){
            return true
        },
        transform: function(data){
        },
        wtxt: '提交中……',
        timeout_msg: '网络无响应',
        timeout_secs: 5000,
        redirect: null
    };
    
    $.extend(options, opt);
    
    
    var submit_data = {};
    $("#" + options['form']).find("[name]").each(function(){
        var name = $(this).attr('name');
        var value = $.trim($(this).val());
        submit_data[name] = value;
     
    });
    
    var url = $("#" + options['form']).attr('action');
    
    var not_empty = options['not_empty'];
    for (var i = 0; i < not_empty.length; i++) {
        if (submit_data[not_empty[i]] == null ||
        submit_data[not_empty[i]].length == 0) {
            show_message_box('信息填写不完整！');
            return false;
        }
    }
    
    var validate = options['validate'];
    var ret = validate(submit_data);
    if (ret === false) 
        return false;
    
    var transform = options['transform'];
    transform(submit_data);
    
    var timeout_id = setTimeout("show_message_box('" + options['timeout_msg'] +
    "');", options['timeout_secs']);
    
    show_wait_message_box(options['wtxt']);
    
    $.post(url, submit_data, function(ret){
        clearTimeout(timeout_id);
        r = eval("(" + ret + ")");
        if (r['code'] == 1) {
            var success = options['success'];
            success(r);
        }
        else {
            var error = options['error'];
            error(r);
            
        }
    });
    
    return true;
    
}

function delay_redirect(url){
    setTimeout(function(){
        location.href = site_url + url;
    }, 500);
}

$(function(){

    $('.ck_editor').each(function(){
        var id = $(this).attr('id');
        replace_editor(id);
    })
    
    $.ajaxSetup({
        beforeSend: function(XMLHttpRequest){
            XMLHttpRequest.setRequestHeader("request_type", "ajax");
        }
    });
    
});

function set_ckeditor_val(){
    $(".ck_editor").each(function(){
        var id = $(this).attr('id');
        $(this).val(CKEDITOR['instances'][id].getData());
    });
    return true;
}

function show_message_box(content){
    $('#message_box').html(content).dialog({
        modal: false,
        resizable: false,
        buttons: {
            "确定": function(){
                $("#message_box").dialog("destroy");
            }
        }
    });
}

function show_wait_message_box(content){
    // $('#message_box').dialog('destroy');
    content = "<p>" + content + "</p>" + "<img src=" + site_url +
    "/templates/img/wait_icon.gif />";
    $('#message_box').html(content).dialog({
        resizable: false,
        modal: true,
        beforeClose: function(){
            return false;
        }
    });
}

function show_confirm_message_box(content, fun1, fun2){
    //$('#message_box').dialog('destroy');
    content = "<p>" + content + "</p>";
    $("#message_box").html(content).dialog({
        buttons: {
            "是": function(){
                if (fun1 != undefined) 
                    fun1();
                $("#message_box").dialog("destroy");
            },
            '否': function(){
                if (fun2 != undefined) 
                    fun2();
                $("#message_box").dialog("destroy");
            }
        }
    })
}

function get_ajax_result(data){
    var r = eval('(' + data + ')');
    return r;
    
}


function process_delete_action(url){
    show_confirm_message_box('您确定要删除吗？', function(data){
        $.post(url, function(data){
            var r = get_ajax_result(data);
            if (r['code'] == 1) {
				show_message_box('删除成功');
                location.reload();
            }
            else {
                show_message_box(r['info']);
            }
        });
    });
}

function process_restore_action(url){
    show_confirm_message_box('您确定要恢复吗？', function(data){
        $.post(url, function(data){
            var r = get_ajax_result(data);
            if (r['code'] == 1) {
				show_message_box('恢复成功');
                location.reload();
            }
            else {
                show_message_box(r['info']);
            }
        });
    });
}

function timeout_message(message){
    var str = arguments[1] || '';
    timeout_id = setTimeout("show_message_box('" + message + "');" + str, 5);
    return timeout_id;
}

function clear_timeout(timeoutid){
    clearTimeout(timeoutid);
}

function show_response_message(data){
    data = eval("(" + data + ")");
    show_form_message_box("'" + data.data + "'");
}

function destroy_wait_message_box(){
    $('#message_box').dialog('destroy');
}

function register_pagination($content_type){
    $content_list = "#" + $content_type + "_list";
    $(".chn").live("click", function(){
        $link = $(this).attr("href");
        $.get($link, function(data){
            $($content_list).html(data);
        });
        return false;
    });
}
