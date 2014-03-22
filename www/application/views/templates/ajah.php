<? if ($_template == 'admin' && $LOADTYPE == LT_STANDART && Request::current()->is_initial()) { ?>
    <h4>&rarr; <?=$title?></h4>
<? } ?>
<?=$content?>

<? if ($LOADTYPE == LT_LOADCONTENT) { ?>
    <div class="fl_r btn btn-warning closecontent">Отмена</div>
<? } ?>

<script>
	Navigation.setTitle('<?= ($_seo->loaded()) ? $_seo->title : $title ?>');
</script>