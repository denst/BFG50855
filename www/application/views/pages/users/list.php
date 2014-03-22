<? /*
<div id="menu_tree">
	<a href="/admin">Главное меню</a> -> <a href="/users">Пользователи</a>
</div>
*/ ?>

<ul id="tabs">
    <li><a href="#" name="#tab1">Пользователи</a></li>
</ul>

<div id="content">

    <div class="btn mt10px loadcontent" data-link="/users/add">Создать</div>

    <div id="loadcontent-container"></div>

    <?=$pager?>
    <div id="tab1" class="tab">
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <th>№</th>
                <th>ФИО</th>
                <th>Логин</th>
                <th>Email</th>
                <th>Последний вход</th>
                <th>Админ</th>
                <th style="width: 150px">Управление</th>
            </tr>
                <? foreach($users as $usr) { ?>
                <tr>
                    <td><?=$usr->id?></td>
                    <td><?=$usr?></td>
                    <td><?=$usr->username?></td>
                    <td><?=$usr->email?></td>
                    <td><?=Text::humanDateTime($usr->last_login)?></td>
                    <td><a class="senddata-token" href="/users/toggle/<?=$usr->id?>"><?=$usr->has('roles',ROLE_ADMIN) ? 'Да' : 'Нет'?></a></td>
                    <td>
                        <img class="loadcontent" data-link="/users/edit/<?=$usr->id?>" src="/themes/images/admin/edit.png" />
                        <div class="loadcontent btn btn-small" data-link="/users/password/<?=$usr->id?>">Пароль</div>
                        <div style="display: inline-block;" class="loadcontent" data-link="/users/delete/<?=$usr->id?>"><img src="/themes/images/admin/delete.png" /></div>
                    </td>
                </tr>
                <? } ?>
        </table>
    </div>
    <?=$pager?>
</div>
