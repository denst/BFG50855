<style>
    #email-block{
        padding-top: 8px;
        padding-left: 8px;
    }
    #save-block{
        text-align: right;
        padding-right: 10px;
    }
    #area-block{
        padding-left: 10px;
    }
</style>
<ul id="tabs">
    <li><a href="#" name="#tab1">Настройки</a></li>
</ul>

<div id="content">

    <div id="loadcontent-container"></div>

    <div id="setting-tab" class="tab">
        <form class="board" id="settings-form">
            <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="save-block" colspan="2" style="text-align: right">
                        <div data-link="/admin/settings" data-input="#settings-form" class="btn btn-warning senddata-token">Сохранить</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" id="area-block" style="text-align: left">
                        <input type="checkbox" name="empty_areas" <?= ($settings['empty_areas'] == 'on')? 'checked': '';?>> Включать пустые районы</td>
                </tr>
                <tr>
                    <td class="td7" id="email-title">Email админа</td>
                    <td id="email-block" style="text-align: left">
                        <input data-validation="notempty;isemail" name="admin_email" type="text" class="span7" value="<?= $settings['admin_email']?>">
                    </td>
                </tr>
                <tr>
                    <td class="td7">Текст формы регистрации</td>
                    <td><textarea class="ckeditor" style="height: 250px;" id="form_registration" name="form_registration"><?= $settings['form_registration']?></textarea></td>
                </tr>
                <tr>
                    <td class="td7">Текст письма уведомления о регистрации</td>
                    <td><textarea class="ckeditor"  style="height: 250px;" id="form_registration_email" name="form_registration_email"><?= $settings['form_registration_email']?></textarea></td>
                </tr>
                <tr>
                    <td class="td7">Текст формы авторизации</td>
                    <td><textarea class="ckeditor" style="height: 250px;" id="form_authorization" name="form_authorization"><?= $settings['form_authorization']?></textarea></td>
                </tr>
                <tr>
                    <td class="td7">Текст формы восстановления пароля шаг 1</td>
                    <td><textarea class="ckeditor" style="height: 250px;" id="recoverypassword_step1" name="recoverypassword_step1"><?= $settings['recoverypassword_step1']?></textarea></td>
                </tr>
                <tr>
                    <td class="td7">Текст формы восстановления пароля шаг 2</td>
                    <td><textarea class="ckeditor" style="height: 250px;" id="recoverypassword_step2" name="recoverypassword_step2"><?= $settings['recoverypassword_step2']?></textarea></td>
                </tr>
                <tr>
                    <td class="td7">Текст письма восстановления пароля</td>
                    <td><textarea class="ckeditor" style="height: 250px;" id="recoverypassword_email" name="recoverypassword_email"><?= $settings['recoverypassword_email']?></textarea></td>
                </tr>
            </table>
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