<ul id="tabs">
    <? foreach($countries as $country) { ?>
        <li><a href="#" name="#tab<?=$country->id?>"><?=$country->name?></a></li>
    <? } ?>
</ul>

<div id="content" class="content-ad">
    <div id="add-ad" class="btn mt10px ml10px" data-toggle="modal"  href="#AdModal">Cоздать</div>
    <span class="btn mt10px ml10px" onclick="deleteChecked()">Удалить помеченные</span>
    
    <div id="loadcontent-container"></div>
    <? foreach($countries as $country) { ?>
    <div id="tab<?= $country->id ?>" class="tab">
        <div id="selectall-container">
            <span>Выделить всё</span>
            <input class="select_all" id="<?=$country->id?>" type="checkbox">
        </div>
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <th>№</th>
                <th>Название</th>
                <th>Расположение</th>
                <th>Редактировать</th>
                <th>Удалить</th>
            </tr>
                <?
                    eval('$ads = $ads_'.$country->id.';');
                    eval('$ader = $ader_'.$country->id.';');
                    $index = 1;
                ?>
                <? foreach($ads as $ad) { ?>
                <tr data-id="<?=$ad->id?>" class="ad">
                    <td><?=$index++?></td>
                    <td><?=$ad->name?></td>
                    <td><?=$ad->position->name?></td>
                    <td>
                        <div id="<?=$ad->id?>" class="change-ad" data-toggle="modal"  href="#AdModal"><img style="cursor: pointer" src="/themes/images/admin/edit.png" /></div>
                        <input type="hidden" id="ad-name-<?=$ad->id?>" value="<?=$ad->name?>">
                        <input type="hidden" id="ad-code-<?=$ad->id?>" value="<?=htmlentities($ad->code)?>">
                        <input type="hidden" id="ad-position-<?=$ad->id?>" value="<?=$ad->position_id?>">
                    </td>
                    <td>
                        <input class="delete_<?=$country->id?>" type="checkbox" name="delete" value="delete" onclick="setDelete(this)" />
                    </td>
                </tr>
                <? } ?>
                <?=$ader?>
        </table>
    </div>
    <? } ?>
</div>

<div class="widget widget-padding span12">
    <div style="display: none;" id="AdModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">✕</button>
          <h3>Редактор блоков рекламы</h3>
        </div>
        
        <form id="ad-form" class="form-horizontal" action="" method="POST">
            <input type="hidden" id="countries-id" name="countries_id" value="">

            <div class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Название</label>
                    <div class="controls">
                        <input data-validation="notempty" type="text" id="ad-name" name="name">
                      <label id="error-name" class="validation-error" style="display:none" for="error-name"></label>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Расположение</label>
                    <div class="controls">
                        <select name="position_id" id="ad-position">
                            <? foreach ($positions as $position):?>
                                <option value="<?=$position->id?>"><?=$position->name?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Код</label>
                    <div class="controls">
                        <textarea data-validation="notempty" id="ad-code" name="code"></textarea>
                        <label id="error-code" class="validation-error" style="display:none" for="error-name"></label>
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

<script type="text/javascript">
    $(document).ready(function(){
        function resetTabs(){
            $("#content .tab").hide(); // Скрываем содержание
            $("#tabs a").attr("id",""); //Сбрасываем id      
        }
        resetTabs();
    
        $("#tabs li:first a").attr("id","current"); // Активируем первую закладку
        $('#countries-id').val('1');
        $("#content .tab:first").fadeIn(); // Показываем содержание первой закладки
        
        $("#tabs a").on("click",function(e) {
            var id = $(this).attr('name').replace('#tab', '');
            e.preventDefault();
            if ($(this).attr("id") == "current"){ //Определение текущейй закладки
                $('#select_all').on("click",function(){
                    checkChecked();
                }); 
                return       
            } else {
                resetTabs();
                $(this).attr("id","current"); // Активируем текущую закладку
                $('#countries-id').val(id);
                $($(this).attr('name')).fadeIn(); // Показываем содержание текущей закладки
            }
        });
        
        function checkChecked(id) {
            var checkboxes = $('.delete_' + id);
            if($('.select_all[id="' + id + '"]').is(':checked')) {
                checkboxes.each(function() {
                    log('ad');
                    log(this);
                    $(this).attr('checked', 'checked');
                    $(this).parents('.ad').addClass('deletethis');
                });

            } else {
                checkboxes.each(function() {
                    log('remove');
                    log(this);
                    $(this).removeAttr('checked');
                    $(this).parents('.ad').removeClass('deletethis');
                });
            }
        }
        
        $('.select_all').on("click",function(){
            var id = $(this).attr('id');
            log(id);
            checkChecked(id);
        }); 

        $('#add-ad').click(function(){
            $('.ad-save').attr('data-link', '/ad/add');
            $('#ad-name').val('');
            $('#ad-code').val('');
        });
        $('.change-ad').click(function(){
            var id = $(this).attr('id');
            $('.ad-save').attr('data-link', '/ad/edit/' + id);
            $('#ad-name').val($('#ad-name-' + id).val());
            $('#ad-code').val($('#ad-code-' + id).val());
            $('#ad-position').val($('#ad-position-' + id).val());
        });
    });
</script>
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
        var ads = [];
        
        $('.deletethis').each(function(){
            ads.push($(this).attr('data-id'));
        });
        
        Message.sendData('/ad/deleteitems',{
            system_case: CORE.token,
            ads: ads
        });
    }
</script>
