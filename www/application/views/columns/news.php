<div class="news-container">

    <? if (Request::current()->is_ajax() || $location->search == 'country') { ?>

        <div class="head">
            <h2>Новости сайта</h2>
        </div>
        <div class="news">
            <? foreach(ORM::factory('blog')->where('blog_types_id','=',1)->order_by('date','desc')->find_all() as $new) { ?>
            <p class="date"><?=Text::humanDate($new->date)?></p>
            <p><a href="/news/show/<?=$new->id?>"><?=Text::limit_chars($new->name, 100, '...')?></a></p>
            <? } ?>
        </div>
    <? } elseif (Controller_News::$ajax_generated == FALSE) { Controller_News::$ajax_generated = TRUE; ?>

        <script type="text/javascript">
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '/news/getlast',
                    dataType: 'html',
                    success: function(data)
                    {
                        $('.news-container').html(data);
                    }
                });
            });
        </script>

        <? } ?>

</div>
