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
                                    <div style="cursor: pointer" id="city-add" data-toggle="modal" href="#ImportModal" onclick="return(false)">
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
</style>
<div id="import-cities" class="widget widget-padding span12">
    <div style="display: none;" id="ImportModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">✕</button>
          <h3>Импорт городов</h3>
        </div>
        
        <form class="form-horizontal" action="" method="POST">
            <div class="modal-body">

                <div class="row">
                  <div class="column">
                      <div>
                        <select name="" id="">
                            <option value="">Россия</option>
                            <option value="">Украина</option>
                            <option value="">Белорусь</option>
                        </select>
                      </div>
                      <div>
                        <select name="" id="">
                            <option value="">Регион</option>
                            <option value="">Регион</option>
                            <option value="">Регион</option>
                        </select>
                      </div>
                      <div style="margin-bottom: 10px;">Имеется городов: 23</div>
                      <textarea name="" id="" cols="30" rows="20"></textarea>
                  </div>
                  <div class="column">
                      <div style="margin-bottom: 20px;">
                          <button class="btn btn-warning" disabled>Запись в БД</button>
                      </div>
                      <div style="margin-bottom: 20px">
                          <button class="btn">Предварительный просмотр</button>
                      </div>
                      <div style="margin-bottom: 10px;">Импорт из списка</div>
                      <textarea name="" id="" cols="30" rows="20"></textarea>
                  </div>
                    <div class="column" style="width: 440px;">
                      <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                          <thead>
                              <tr>
                                  <td>Имя города</td>
                                  <td>Имя РП</td>
                                  <td>Имя ПП</td>
                                  <td>Домен</td>
                                  <td>Широта</td>
                                  <td>Долгота</td>
                                  <td>Район</td>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>Рославь</td>
                                  <td>Рославле</td>
                                  <td>Рославля</td>
                                  <td>roslavi</td>
                                  <td>0</td>
                                  <td>0</td>
                                  <td></td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="senddata-token btn btn-primary ad-save" data-link="" data-input="#ad-form">Сохранить</div>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </form>
    </div>
</div>