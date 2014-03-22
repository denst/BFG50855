<ul id="tabs">
    <li><a href="#" name="#tab1">Настройки</a></li>
</ul>

<div id="content">

    <div id="loadcontent-container"></div>
    <div id="setting-tab" class="tab">
        <form class="board" id="settings-form">
            <div class="accordion" id="accordion_id">
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse1" onclick="return(false)">
                    Денежные единицы
                  </a>
                </div>
                <div id="collapse1" class="accordion-body collapse in">
                  <div class="accordion-inner">
                    <div id="currencies-container">
                        <div class="currencies-row">
                            <table>
                                <thead>
                                    <tr>
                                        <td colspan="2" style="width: 50%">
                                            Курс валют (по отношению  к рублю)
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($currencies as $currency):?>
                                        <? if($currency->name != 'руб.'):?>
                                            <tr>
                                                <td><input name="courses[]" type="text" value="<?=$currency->course?>"></td>
                                                <td class="currencies-title"><?=$currency->name?></td>
                                            </tr>
                                        <? endif?>
                                   <? endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="currencies-row">
                            <table>
                                <thead>
                                    <tr>
                                        <td colspan="2" style="width: 50%" class="left-border">
                                            Присвоение валюты странам
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($countries as $country):?>
                                        <tr>
                                            <td>
                                                <select name="currencies_<?=$country->id?>">
                                                    <? foreach ($currencies as $currency):?>
                                                        <? if($country->price_types_id == $currency->id):?>
                                                            <option value="<?=$currency->id?>" selected><?=$currency->name?></option>
                                                        <? else:?>
                                                            <option value="<?=$currency->id?>"><?=$currency->name?></option>
                                                        <? endif?>
                                                    <? endforeach;?>
                                                </select>
                                            </td>
                                            <td>Валюта <?=$country->name_rp?></td>
                                        </tr>
                                   <? endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse2" onclick="return false;">
                    Email админа
                  </a>
                </div>
                <div id="collapse2" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7" id="email-title">Email админа</td>
                            <td id="email-block" style="text-align: left">
                                <input data-validation="notempty;isemail" name="admin_email" type="text" class="span7" value="<?= $settings['admin_email']?>">
                            </td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse3" onclick="return(false)">
                    Текст формы регистрации
                  </a>
                </div>
                <div id="collapse3" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7">Текст формы регистрации</td>
                            <td><textarea class="ckeditor" style="height: 250px;" id="form_registration" name="form_registration"><?= $settings['form_registration']?></textarea></td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse4" onclick="return(false)">
                    Текст письма уведомления о регистрации
                  </a>
                </div>
                <div id="collapse4" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7">Текст письма уведомления о регистрации</td>
                            <td><textarea class="ckeditor"  style="height: 250px;" id="form_registration_email" name="form_registration_email"><?= $settings['form_registration_email']?></textarea></td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse5" onclick="return(false)">
                    Текст формы авторизации
                  </a>
                </div>
                <div id="collapse5" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7">Текст формы авторизации</td>
                            <td><textarea class="ckeditor" style="height: 250px;" id="form_authorization" name="form_authorization"><?= $settings['form_authorization']?></textarea></td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse6" onclick="return(false)">
                    Текст формы восстановления пароля шаг 1
                  </a>
                </div>
                <div id="collapse6" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7">Текст формы восстановления пароля шаг 1</td>
                            <td><textarea class="ckeditor" style="height: 250px;" id="recoverypassword_step1" name="recoverypassword_step1"><?= $settings['recoverypassword_step1']?></textarea></td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse7" onclick="return(false)">
                    Текст формы восстановления пароля шаг 2
                  </a>
                </div>
                <div id="collapse7" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7">Текст формы восстановления пароля шаг 2</td>
                            <td><textarea class="ckeditor" style="height: 250px;" id="recoverypassword_step2" name="recoverypassword_step2"><?= $settings['recoverypassword_step2']?></textarea></td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_id" href="#collapse8" onclick="return(false)">
                    Текст письма восстановления пароля
                  </a>
                </div>
                <div id="collapse8" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="td7">Текст письма восстановления пароля</td>
                            <td><textarea class="ckeditor" style="height: 250px;" id="recoverypassword_email" name="recoverypassword_email"><?= $settings['recoverypassword_email']?></textarea></td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
                <div>
                    <table>
                        <tr>
                            <td>
                                <input type="checkbox" name="empty_areas" <?= ($settings['empty_areas'] == 'on')? 'checked': '';?>> Включать пустые районы</td>
                            </td>
                            <td>
                                <div data-link="/admin/settings" data-input="#settings-form" class="btn btn-warning senddata-token">Сохранить</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    if (CKEDITOR.instances['form_registration']) {
        delete CKEDITOR.instances['form_registration'];
    }
    if (CKEDITOR.instances['form_registration_email']) {
        delete CKEDITOR.instances['form_registration_email'];
    }
    if (CKEDITOR.instances['form_authorization']) {
        delete CKEDITOR.instances['form_authorization'];
    }
    if (CKEDITOR.instances['recoverypassword_step1']) {
        delete CKEDITOR.instances['recoverypassword_step1'];
    }
    if (CKEDITOR.instances['recoverypassword_step2']) {
        delete CKEDITOR.instances['recoverypassword_step2'];
    }
    if (CKEDITOR.instances['recoverypassword_email']) {
        delete CKEDITOR.instances['recoverypassword_email'];
    }

    CKEDITOR.replace( 'form_registration' );
    CKEDITOR.replace( 'form_registration_email' );
    CKEDITOR.replace( 'form_authorization' );
    CKEDITOR.replace( 'recoverypassword_step1' );
    CKEDITOR.replace( 'recoverypassword_step2' );
    CKEDITOR.replace( 'recoverypassword_email' );
</script>