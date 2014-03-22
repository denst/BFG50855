<ul id="tabs">
    <li><a href="#" name="#tab1">Страны</a></li>
</ul>

<?=$pager?>
<div id="content">

    <div class="loadcontent btn mt10px" data-link="/countries/add">Создать</div>

    <div id="loadcontent-container"></div>

    <div id="tab1" class="tab">
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <th>№</th>
                <th>Страна</th>
                <th>Регионов</th>
                <th style="width: 150px">Управление</th>
            </tr>
                <? foreach($countries as $country) { ?>
                <tr>
                    <td><?=$country->id?></td>
                    <td><a href="/regions/list/<?=$country->id?>"><?=$country->name?></a></td>
                    <td><?=$country->regions->get_model_count()?></td>
                    <td>
                        <img class="loadcontent" data-link="/countries/edit/<?=$country->id?>" src="/themes/images/admin/edit.png" />
                        <img class="loadcontent" data-link="/countries/delete/<?=$country->id?>" src="/themes/images/admin/delete.png" />
                    </td>
                </tr>
                <? } ?>
        </table>
    </div>
</div>
<?=$pager?>
