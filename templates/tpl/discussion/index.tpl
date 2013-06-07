{literal}
<script type="text/javascript">
    $(function () {
        $.get(site_url + "/discussion/all", function (data) {
            $('#discussion_list').html(data);
        });
        register_pagination("discussion");
    })
</script>
{/literal}
<div id="personal_show_widget" class="widget max_width"><h2 class="water2">讨论贴</h2>

    <div class="widget_content">
        <div id="discussion_list">
        </div>
    </div>
</div>
