<ul id="tabs">
    <li><a href="#" name="#tab1">Страницы</a></li>
</ul>

<div id="content">

    <div class="loadcontent btn mt10px" data-link="/page/save">Создать</div>
    <div id="loadcontent-container"></div>

    <div id="tab1" class="tab">
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <th>№</th>
                <th>Название</th>
                <th>URL</th>
                <th style="width: 150px">Управление</th>
            </tr>
                <? foreach($pages as $page) { ?>
                <tr>
                    <td><?=$page->id?></td>
                    <td><?=$page->name?></td>
                    <td><?=$page->lat_name?></td>
                    <td>
                        <img class="loadcontent" data-link="/page/save/<?=$page->id?>" src="/themes/images/admin/edit.png" />
                        <img class="senddata" data-link="/page/delete/<? //$page->id?>" src="/themes/images/admin/delete.png" />
                    </td>
                </tr>
                <? } ?>
        </table>
    </div>
</div>