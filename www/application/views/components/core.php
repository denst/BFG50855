<script type="text/javascript">
    CORE = {
        token: '<?=Security::token()?>',
        apptitle: '<?=APPLICATION_TITLE?>',
        user_id: '<?=$auth->logged_in() ? $user->id : '0'?>'
    }

    <? if ($auth->logged_in()) { ?>
        Navigation.caching = false;
    <? } ?>

</script>
