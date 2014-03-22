<? /* Система оповещений о событиях */ ?>
<div id="notifies"></div>
<!--Template for notify preview-->
<script id="t-notify" type="text/x-jquery-tmpl">
    <div class="notify" id="notify-${id}" style="opacity: 0;">
        <div class="notify-close" onclick="$(this).parent().remove()"></div>
        <div class="notify-head">
            <div title="${type.text}" class="notify-head-icon"><img src="/themes/images/components/notify/${type.icon}" /></div>
            <div class="notify-text">{{html text}}</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</script>
