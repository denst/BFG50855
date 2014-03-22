<style>
    #areas_import{
        min-height: 108px;
        width: 225px;
        overflow-y: auto;
        float: right;
        margin-top: -30px;
    }
    #items-list td{
        padding: 5px;
    }
</style>
<ul id="tabs">
    <li><a href="#" name="#tab1">Районы города <?=$city->name?></a></li>
</ul>

<?=$pager?>
<div id="content">
    <a href="/cities/list/<?=$city->regions_id?>" class="btn mt10px mb15px">К городам <?=$city->region->name_pp?></a>
    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
        <tr>
            <td id="loadcontent-container"></td>
            <td>
                <div id="addarea">
                    <input type="text" name="name" data-validation="notempty" />
                    <div class="btn senddata" data-link="/citiesareas/add/<?=$city->id?>" data-input="#addarea">Добавить район</div>
                    <span>Выбрать другой город: <?=Form::select('city',$cities,$city->id)?></span>
                    <script type="text/javascript">
                        $('select[name="city"]').change(function(){
                            Navigation.loadPage('/citiesareas/list/'+$(this).val());
                        });
                    </script>
                </div>
                <div id="areas_import">
                    <textarea name="items" data-validation="notempty" style="height: 300px;"></textarea>
                    <div data-input="#areas_import" data-link="/citiesareas/add/<?=$city->id?>" class="btn senddata">Импорт районов</div>
                </div>
                <div style="min-height: 100px; width: 700px; overflow-y: auto; float: left;">
                    <table id="items-list" width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>№</th>
                            <th>Имя района</th>
                            <th>Имя района - род. п.</th>
                            <th>Транслитированное имя района</th>
                            <th style="width: 150px">Управление</th>
                        </tr>
                            <? foreach($areas as $area) { ?>
                            <tr>
                                <td><?=$area->id?></td>
                                <td><?=$area->name?></td>
                                <td><?=$area->rp_name?></td>
                                <td><?=$area->en_name?></td>
                                <td>
                                    <img class="loadcontent" data-link="/citiesareas/edit/<?=$area->id?>" src="/themes/images/admin/edit.png">
                                    <img class="senddata really" style="cursor: pointer;" data-link="/citiesareas/delete/<?=$area->id?>" src="/themes/images/admin/delete.png" />
                                </td>
                            </tr>
                            <? } ?>
                            <? if ($areas->count() == 0) { ?>
                            <tr>
                                <td colspan="3">Пока что нет ни одного района</td>
                            </tr>
                            <? } ?>
                    </table>
                </div>
                
                <div class="clear"></div>
            </td>
        </tr>
    </table>
</div>
<?=$pager?>
