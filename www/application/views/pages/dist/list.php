<ul id="tabs">
    <? foreach($countries as $country) { ?>
        <li><a href="#" name="#tab<?=$country->id?>"><?=$country->name?></a></li>
    <? } ?>
</ul>

<div id="content">
    <div class="loadcontent btn mt10px ml10px" data-link="/dist/add">Создать</div>
    <div id="loadcontent-container"></div>
    <? foreach($countries as $country) { ?>
    <div id="tab<?= $country->id ?>" class="tab">
        <table width="100%" border="1" align="center" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
            <tr>
                <th>№</th>
                <th>Название</th>
                <th>URL</th>
                <th style="width: 150px">Управление</th>
            </tr>
                <?
                    eval('$pages = $pages_'.$country->id.';');
                    eval('$pager = $pager_'.$country->id.';');
                ?>
                <? foreach($pages as $page) { ?>
                <tr>
                    <td><?=$page->id?></td>
                    <td><?=$page->name?></td>
                    <td><?=$page->url?></td>
                    <td>
                        <img class="loadcontent" data-link="/dist/edit/<?=$page->id?>" src="/themes/images/admin/edit.png" />
                        <img class="loadcontent" data-link="/dist/delete/<?=$page->id?>" src="/themes/images/admin/delete.png" />
                    </td>
                </tr>
                <? } ?>
                <?=$pager?>
        </table>
    </div>
    <? } ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        function resetTabs(){
            $("#content .tab").hide(); // Скрываем содержание
            $("#tabs a").attr("id",""); //Сбрасываем id      
        }
        resetTabs();
    
        //$("#content div").hide(); // Скрываем все содержание при инициализации
        $("#tabs li:first a").attr("id","current"); // Активируем первую закладку
        $("#content .tab:first").fadeIn(); // Показываем содержание первой закладки
        
        $("#tabs a").on("click",function(e) {
            e.preventDefault();
            if ($(this).attr("id") == "current"){ //Определение текущейй закладки
                return       
            } else {
                resetTabs();
                $(this).attr("id","current"); // Активируем текущую закладку
                $($(this).attr('name')).fadeIn(); // Показываем содержание текущей закладки
            }
        });
    });
</script>


















<script>
//кладки
 //         }
//        }
//    })()
</script>









<script>
//кладки
 //         }
//        }
//    })()
</script>