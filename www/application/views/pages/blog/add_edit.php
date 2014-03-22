<div id="create-training">
    <h2>Редактировать запись</h2>
    <table id="redakt" border="1" cellspacing="0" cellpadding="0" bordercolor="#ccc">
        <thead>
            <tr>
                <th style="width: 150px;"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="td7">Отображение записи</td>
                <td style="text-align: left;">
                    <p><?=Form::checkbox('show','1',(bool)$blog->show)?> Запись видна посетителям</p>
                    <p class="fnt-small">Если желаете сначала удостовериться в том, что все правильно отформатировано, не ставьте эту галочку до того момента, пока все не отладите.</p>
                </td>
            </tr>
            <tr>
                <td class="td7">Название</td>
                <td><?=Form::input('name',$blog->name)?></td>
            </tr>
            <tr>
                <td class="td7">Дата</td>
                <td>
                    <?
                    echo Form::input('date', date('d.m.Y',$blog->date), array('data-validation'=>'notempty;isdate'));
                    echo Form::validation('date');
                    ?>
                </td>
            </tr>
            <tr>
                <td class="td7">Текст</td>
                <td>
                    <?=Form::textarea('text',$blog->text,array('id'=>'text','style' => 'height: 250px;'))?>
                    <?=Form::validation('text')?>
                </td>
            </tr>
            <tr>
                <td class="td7">SEO - Ключевые слова</td>
                <td><?=Form::input('keywords',$blog->keywords)?></td>
            </tr>
            <tr>
                <td class="td7">SEO - Описание</td>
                <td><?=Form::textarea('desc',$blog->desc)?></td>
            </tr>
            <tr>
                <td class="td7">Картинка на заголовок (рекомендуемый размер: 230х140)</td>
                <td style="text-align: left;" id="avatar_link">
                    <div class="mini-button fl_r" onclick="$('#image-preview,input[name=photo_id]').val('');">без изображения</div>
                    <div id="upload-image"></div>
                    <p>Выбранный файл:</p>
                    <div id="image-preview">
                        <? if (empty($blog->photo_id)) { ?>отсутствует<? } else { ?>
                        <a target="_blank" href="<?=$blog->photo_id?>"><img style="width: 150px" src="<?=$blog->photo_id?>"/></a>
                        <? } ?>
                    </div>
                    <?=Form::hidden('photo_id',$blog->photo_id)?>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="mt5px mb10px ml10px btn btn-primary senddata-token" data-link="<?=Request::current()->url()?>" data-input="#create-training">Применить изменения</div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
    if (CKEDITOR.instances['text']) {
        delete CKEDITOR.instances['text'];
    }
    CKEDITOR.replace('text');
    //Core.editor('#text');

    Upload.init('upload-image','<?= Security::token()?>',function(data) {
        $('#image-preview').html('<a target="_blank" href="'+data.path+'"><img style="width: 150px" src="'+data.thumb+'"/></a>');
        $('#avatar_link input').val(data.id);
        Notify.message('Фотография загружена', 'completed');
    },'*.jpg;*.jpeg;*.png;*.gif','Изображения','normal');
</script>