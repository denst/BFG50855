<div class="btn mb10px loadcontent" data-link="/posts_flats/add">Создать</div>

<?=$flats_pager?>
<table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
    <tr>
        <th>id</th>
        <th>НАЗВАНИЕ</th>
        <th>URL</th>
        <th>АКТИВНОСТЬ</th>
        <th class="img"><img src="/themes/images/admin/plus.png" /></th>
    </tr>
    <? foreach($flats as $flat) { ?>
    <tr>
        <td class="td1"><?=$flat->id?></td>
        <td class="td2"><?=$flat->advert->avatar()?> <?=$flat->advert->title?></td>
        <td class="td3"><?=$flat->advert->alt?></td>
        <td class="td4"><?=$flat->advert->published ? 'Опубликовано' : 'Скрыто'?></td>
        <td class="td5">
            <span class="niceCheck"><input type="checkbox"/></span>
        </td>
        <td class="td6">
            <a href="/posts_flats/edit/<?=$flat->id?>"><img src="/themes/images/admin/brose.png" /></a>
            <img class="loadcontent" data-link="/posts_flats/edit/<?=$flat->id?>" src="/themes/images/admin/edit.png" />
            <img class="loadcontent" data-link="/posts_flats/delete/<?=$flat->id?>" src="/themes/images/admin/delete.png" />
        </td>
    </tr>
    <? } ?>
</table>
<?=$flats_pager?>

