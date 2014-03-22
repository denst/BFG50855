<h3><? if ($page->loaded()) { ?>Редактирование страницы "<?=$page->name?>"<? } else { ?>Создание страницы<? } ?></h3>

<table id="redakt" border="1" cellspacing="0" cellpadding="0" bordercolor="#ccc">
    <tr>
        <td class="td7">Название</td>
        <td><input data-validation="notempty" type="text" name="name" value="<?=$page->name?>" /></td>
    </tr>
    <tr>
        <td class="td7">URL</td>
        <td><input data-validation="notempty" type="text" name="lat_name" value="<?=$page->lat_name?>" /></td>
    </tr>
    <tr>
        <td class="td7">Текст страницы</td>
        <td><textarea style="height: 250px;" id="text" name="text"><?=$page->text?></textarea></td>
    </tr>
    <tr>
        <td colspan="2"><div data-link="/page/save/<?=$page->id?>" data-input="#redakt" class="btn senddata-token">Ок</div></td>
    </tr>
</table>

<script type="text/javascript">
    if (CKEDITOR.instances['text']) {
        delete CKEDITOR.instances['text'];
    }
    CKEDITOR.replace( 'text' );
    //Core.editor('#text');
</script>