<ul id="tabs">
    <li><a href="#" name="#tab1">Квартиры</a></li>
</ul>
<div id="content">
    <div id="loadcontent-container"></div>
    <div id="tab1" class="tab">

        <a class="btn mb10px" href="/posts/save" target="_blank">Создать</a>
        <span class="btn mb10px" onclick="deleteChecked()">Удалить помеченные</span>

        <?=$pager?>
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <th>id</th>
                <th>Картинка</th>
                <th>Название</th>
                <th>Url</th>
                <th>Дата</th>
                <th>Изменить</th>
                <th>Удалить</th>
            </tr>
            <? foreach($adverts as $advert) { ?>
            <tr data-id="<?=$advert->id?>" class="advert">
                <td><?=$advert->id?></td>
                <td><?=$advert->avatar()?></td>
                <td><?=$advert->title?></td>
                <td><?=$advert->link()?></td>
                <td><?=Text::humanDate($advert->time)?></td>
                <td><a target="_blank" href="/posts/save/<?=$advert->id?>"><img src="/themes/images/admin/edit.png" /></a></td>
                <td>
                    <input class="delete" type="checkbox" name="delete" value="delete" onclick="setDelete(this)" />
                </td>
            </tr>
            <? } ?>
        </table>
        <?=$pager?>
    </div>
</div>

<script>
    function setDelete(el) {
        var el = $(el).parent().parent();
        if (el.hasClass('deletethis')) {
            el.removeClass('deletethis');
        } else {
            el.addClass('deletethis');
        }
    }
    
    function deleteChecked() {
        var adverts = [];
        
        $('.deletethis').each(function(){
            adverts.push($(this).attr('data-id'));
        });
        
        Message.sendData('/posts_admin/deleteitems',{
            system_case: CORE.token,
            adverts: adverts
        });
    }
</script>