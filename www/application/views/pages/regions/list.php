<ul id="tabs">
    <li><a href="#" name="#tab1">Регионы <?=$country->name_pp?></a></li>
</ul>

<?=$pager?>
<div id="content">

    <a href="/countries" class="btn mt10px">К странам</a> <div class="loadcontent btn mt10px ml10px" data-link="/regions/add/<?=$country->id?>">Создать</div>
    <span style="position: relative; top: 10px; left: 10px;"><?=Form::filter('Сортировать по ...','sort_regions_by',array(
        'name' => 'Имени',
        'domain' => 'Домену',
        'citycount' => 'Количеству городов',
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
                                <th>Регион</th>
                                <th>Домен</th>
                                <th style="width: 30px">Код</th>
                                <th>Городов</th>
                                <th style="width: 30px">Импорт городов</th>
                                <th style="width: 150px">Управление</th>
                            </tr>
                            <? foreach($regions as $region) { ?>
                            <tr>
                                <td><?=$region->id?></td>
                                <td><a href="/cities/list/<?=$region->id?>"><?=$region->name?></a></td>
                                <td><?=$region->domain?></td>
                                <td><?=!empty($region->code) ? 'Есть' : 'x'?></td>
                                <td><?=$region->cities->get_model_count()?></td>
                                <td>
                                    <div style="cursor: pointer" id="<?=$region->id?>" class="import-cities-button" data-toggle="modal" href="#ImportModal" onclick="return(false)">
                                        <img src="/themes/images/admin/edit.png" />
                                    </div>
                                </td>
                                <td>
                                    <img class="loadcontent setactive" data-link="/regions/edit/<?=$region->id?>" src="/themes/images/admin/edit.png" />
                                    <img class="loadcontent" data-link="/regions/delete/<?=$region->id?>" src="/themes/images/admin/delete.png" />
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
<style>
    #import-cities .modal{
        width: 1015px;
        margin-left: -500px;
        margin-top: -350px;
    }
    #import-cities .row{
        margin: 0px;
    }
    #import-cities select{
        margin-bottom: 20px;
    }
    #import-cities .column{
        float: left;
        margin-right: 30px;
    }
    #import-cities .modal-body{
        max-height: 515px;
    }
    #import-cities thead tr{
       font-weight: bold;
    }
    #import-cities table input{
        width: 100px;
    }
</style>
<div id="import-cities" class="widget widget-padding span12">
    <div style="display: none;" id="ImportModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">✕</button>
          <h3>Импорт городов</h3>
        </div>
        
        <form id="form-import" class="form-horizontal" method="POST">
            <div class="modal-body">

                <div class="row">
                  <div class="column">
                      <div>
                        <select name="" id="">
                            <? foreach ($countries as $cnt):?>
                                <? if($cnt->id == $country->id):?>
                                    <option value="<?=$cnt->id?>" selected><?=$cnt->name?></option>
                                <? else:?>
                                    <option value="<?=$cnt->id?>"><?=$cnt->name?></option>
                                <? endif?>
                            <? endforeach;?>
                        </select>
                      </div>
                      <div>
                        <select id="import-cities-regions" name="regions_id">
                            <? foreach($regions as $region):?>
                                <option value="<?=$region->id?>"><?=$region->name?></option>
                            <? endforeach;?>
                        </select>
                      </div>
                      <div style="margin-bottom: 10px;">Имеется городов: <span id="count_cities"></span></div>
                      <textarea id="current-cities" cols="30" rows="20"></textarea>
                  </div>
                  <div class="column">
                      <div style="margin-bottom: 20px;">
                          <div id="write-import-button" data-link="/cities/import" data-input="#form-import" class="btn btn-warning senddata-token" disabled>Запись в БД</div>
                          <span id="sort" class="btn">Сортировать</span>
                      </div>
                      <div style="margin-bottom: 20px">
                          <span class="btn preview-button">Предварительный просмотр</span>
                      </div>
                      <div style="margin-bottom: 10px;">Импорт из списка</div>
                      <textarea id="new-import-cities" cols="30" rows="20"></textarea>
                  </div>
                    <div class="column" style="width: 440px;">
                      <table id="table-preview-import" width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                          <thead>
                              <tr>
                                  <td>Имя города</td>
                                  <td>Имя РП</td>
                                  <td>Имя ПП</td>
                                  <td>Домен</td>
                                  <td>Широта</td>
                                  <td>Долгота</td>
                                  <td>Удалить</td>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function check_table() {
        if($('#table-preview-import tbody tr').length > 0) {
            $('#write-import-button').removeAttr('disabled');
        }
        else {
            $('#write-import-button').attr('disabled', 'disabled');
        }
    }
    $(document).ready(function(){
        $(".import-cities-button").live('click', function(){
            var id = $(this).attr('id');
            $('#import-cities-regions').val(id);
            var data = {id: id};
            $.ajax({
                type: "POST",
                url: "/cities/getcities",
                data: data,
                dataType: "json",
                success: function(res) {
                    if(res.success == true) {
                        $('#current-cities').val(res.result_cities);
                        $('#count_cities').html(res.count_cities);
                    }
                }
            });
        });
        $('#sort').click(function(){
            sort_textarea($('#current-cities'));
            sort_textarea($('#new-import-cities'));
        });
        function sort_textarea(textarea) {
            var content = textarea.val().split("\n").sort();
            if(content[0] == '')
                content.shift();
            textarea.val(content.join("\n"));
        }
        $('.delete-button').live('click', function(){
            $('#table-preview-import tbody tr[id="' + $(this).attr('id') + '"]').remove();
            check_table();
        });
        $('.preview-button').live('click', function(){
            var data = {cities: $('#new-import-cities').val()};
            $.ajax({
                type: "POST",
                url: "/cities/getcityinfo",
                data: data,
                dataType: "json",
                success: function(res) {
                    if(res.success == true) {
                        for(var i = 0; i < res.cities.length; i++){
                            
                            var td_name = $("<td/>").append($("<input/>", 
                                    {
                                        "name": "name[]",
                                        "type": "text",
                                        "value": res.cities[i]['name']
                                    })); 
                            var td_rp = $("<td/>").append($("<input/>", 
                                    {
                                        "name": "name_rp[]",
                                        "type": "text",
                                        "value": res.cities[i]['name_rp']
                                    })); 
                            var td_pp = $("<td/>").append($("<input/>", 
                                    {
                                        "name": "name_pp[]",
                                        "type": "text",
                                        "value": res.cities[i]['name_pp']
                                    })); 
                            var td_domain = $("<td/>").append($("<input/>", 
                                    {
                                        "name": "domain[]",
                                        "type": "text",
                                        "value": res.cities[i]['domain']
                                    })); 
                            var td_longitude = $("<td/>").append($("<input/>", 
                                    {
                                        "name": "longitude[]",
                                        "type": "text",
                                        "value": res.cities[i]['longitude']
                                    })); 
                            var td_latitude = $("<td/>").append($("<input/>", 
                                    {
                                        "name": "latitude[]",
                                        "type": "text",
                                        "value": res.cities[i]['latitude']
                                    }));
                            var delete_button = $("<td/>").append($("<img/>", 
                                    {
                                        "id": res.cities[i]['id'],
                                        "class": 'delete-button',
                                        "style": "cursor: pointer",
                                        "src": "/themes/images/admin/delete.png",
                                    }));
                                    
                            var tr_item = $("<tr/>", {"id": res.cities[i]['id']}).append(td_name).append(td_rp).
                                    append(td_pp).append(td_domain).
                                    append(td_longitude).append(td_latitude).
                                    append(delete_button);
                            if($('#table-preview-import tbody tr[id="' + res.cities[i]['id'] + '"]').length == 0) {
                                $('#table-preview-import tbody').append(tr_item);
                            }
                            check_table();
                        }
                    }
                }
            });
        });
    });
</script>