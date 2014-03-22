<ul id="tabs">
    <li><a href="#" name="#tab1">Города <?=$region->name_pp?></a></li>
</ul>

<?=$pager?>
<div id="content">
    <a href="/regions/list/<?=$region->countries_id?>" class="btn mt10px">К регионам <?=$region->country->name_pp?></a> <div class="loadcontent btn mt10px ml10px" data-link="/cities/add/<?=$region->id?>">Создать</div>
    <span style="position: relative; top: 10px; left: 10px;"><?=Form::filter('Сортировать по ...','sort_cities_by',array(
        'name' => 'Имени',
        'domain' => 'Домену',
    ));?></span>
    <div id="tab1" class="tab">
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <td id="loadcontent-container"></td>
                <td>
                    <div style="height: 500px; overflow-y: auto">
                        <table id="items-list" width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                            <tr>
                                <th>№</th>
                                <th>Город</th>
                                <th>Домен</th>
                                <th>Районы</th>
                                <th>Код</th>
                                <th style="width: 150px">Управление</th>
                            </tr>
                            <? foreach($cities as $city) { ?>
                            <tr>
                                <td><?=$city->id?></td>
                                <td><?=$city->name?></td>
                                <td><?=$city->domain?></td>
                                <td><?=$city->areas->get_model_count()?> <a href="/citiesareas/list/<?=$city->id?>"><img src="/themes/images/admin/edit.png"/></a></td>
                                <td><?=!empty($city->code) ? 'Есть' : 'x'?></td>
                                <td>
                                    <img class="loadcontent setactive" data-link="/cities/edit/<?=$city->id?>" src="/themes/images/admin/edit.png" />
                                    <img class="loadcontent" data-link="/cities/delete/<?=$city->id?>" src="/themes/images/admin/delete.png" />
                                </td>
                            </tr>
                            <? } ?>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<?=$pager?>
