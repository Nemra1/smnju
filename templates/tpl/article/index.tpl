{literal}
<script type="text/javascript">
    $(function () {
        register_pagination("article");

        $.get("/article/all", function (data) {
            $("#article_list").html(data);
        })
    })
</script>
{/literal}
<div id="personal_show_widget" class="widget max_width"><h2 class="water2">投稿文章</h2>

    <div class="widget_content">
        <div id="article_list">
        </div>
    </div>
</div>
