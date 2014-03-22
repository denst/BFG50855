<div class="span3" id="left-side">

    <?=View::factory('columns/nedv')?>

    <?=View::factory('columns/cabinet')?>

</div>
<? if(isset($_seo)):?>
    <input type="hidden" id="seo_description" value="<?= $_seo->desc?>">
    <input type="hidden" id="seo_keywords" value="<?= $_seo->keywords?>">
    <input type="hidden" id="seo_footer" value="<?= $_seo_footer?>">
    <script type="text/javascript">
        $(document).ready(function(){
            $('meta[name="description"]').attr('content', $('#seo_description').val())
            $('meta[name="keywords"]').attr('content', $('#seo_keywords').val())
            $('#footer-text-container').html($('#seo_footer').val());
        });
    </script>
<? endif?>
<div class="span6 options">
    
    <div class="breadcrumb">
        <? if(isset($area_name) AND 
                isset($commtype_name)):?>
            <a href="/<?= $cat_link?>"><?= $cat_name ?></a>
            -> <a href="/<?= $cat_link?>/<?= $type_link?>"><?= $type_name ?></a>
            -> <a href="/<?= $cat_link?>/<?= $type_link?>/<?= $commtype_link?>"><?= $commtype_name ?></a>
            -> <?= $area_name ?>
            
        <? elseif(isset($commtype_name)):?>
            <a href="/<?= $cat_link?>"><?= $cat_name ?></a>
            -> <a href="/<?= $cat_link?>/<?= $type_link?>"><?= $type_name ?></a>
            -> <?= $commtype_name ?>
                
        <? elseif(isset($area_name) AND isset($type_name)):?>
            <a href="/<?= $cat_link?>"><?= $cat_name ?></a>
            -> <a href="/<?= $cat_link?>/<?= $type_link?>"><?= $type_name ?></a>
            -> <?= $area_name ?>
            
        <? elseif(isset($type_name)):?>
        <a href="/<?= $cat_link?>"><?= $cat_name ?></a>
                -> <?= $type_name ?>
            
        <? elseif(isset($area_name)):?>
            <a href="/<?= $cat_link?>"><?= $cat_name ?></a>
            -> <?= $area_name ?>
                
        <? else: ?>
            <?= $cat_name ?>
        <? endif?>
    </div>
    
    <div class="head" style="height: auto">

        <h1><?=$_seo->h1?></h1>

        <div class="clear"></div>

        <p><?=$_seo->main?></p>
        
    </div>
    
    <div id="area-container">
        <? if(! isset($set_current_arrea)):?>
            <? foreach ($areas as $area):?>

                <? if(isset($commtype_name)):?>
                    <div class="area-item"><a href = "/<?= $cat_link?>/<?= $type_link?>/<?= $commtype_link?>/<?= $area->en_name?>"><?= $area->name?></a></div>

                <? elseif(isset($type_link)):?>
                    <div class="area-item"><a href = "/<?= $cat_link?>/<?= $type_link?>/<?= $area->en_name?>"><?= $area->name?></a></div>

                <? else:?>
                    <div class="area-item"><a href = "/<?= $cat_link?>/<?= $area->en_name?>"><?= $area->name?></a></div>

                <? endif?>
            <? endforeach;?>
        <? endif;?>
    </div>

    <? if(isset($ads)) {
        foreach ($ads as $ad) {
            if($ad->position_id == 1):?>
                <div class="ad-block">
                    <?=$ad->code;?>
                </div>
            <? endif;
        }
    } ?>

    <div>
        <ul class="order_list">
            <? foreach($adverts as $advert) { ?>

            <li>
                <img style="max-width: 180px;" src="<?=$advert->photo->thumb()?>" alt="<?=$advert->title?>" />

                <span class="price"><?=Model_Price::convert_price($advert, $country)?></span>

                <a onclick="Navigation.reloadFullPage('<?=$advert->link()?>');" href="<?=$advert->link()?>"><zagolovok><?=$advert->title?></zagolovok></a>

                <p class="date"><?= Text::humanDate($advert->time)?></p>

                <?=Text::limit_chars($advert->desc, 500, '...');?>

            </li>

            <? } ?>

        </ul>

        <? if ($adverts->count() == 0) { ?>

            <h3>Объявлений нет</h3>

            <p>Извините, но в данный момент здесь нет ни одного объявления.</p>

        <? } ?>

        <? if(isset($ads)) {
            foreach ($ads as $ad) {
                if($ad->position_id == 3):?>
                    <div class="ad-block">
                        <?=$ad->code;?>
                    </div>
                <? endif;
            }
        } ?>

        <?=$pager?>

    </div>
</div>

<div class="span3" id="right-side">

    <? if(isset($news)):?>
        <?=View::factory('columns/news')?>
    <? endif?>
    
    <? if(isset($ads)) {
        foreach ($ads as $ad) {
            if($ad->position_id == 2):?>
                <div class="ad-side">
                    <?=$ad->code;?>
                </div>
            <? endif;
        }
    } ?>

    <?=View::factory('columns/posts',array(

        'cat' => $cat,

        'type' => $type

    ))?>

</div>
    
<script type="text/javascript">
//    $(document).ready(function(){
//        $(window).resize(function(){
//            if($(window).width() <= 800) {
//                if($("#left-side:has('.news-contain')").length == 0)
//                    $('.news-container').appendTo('#left-side');
//                if($("#left-side:has('.ad-side)").length == 0)
//                    $('.ad-side').appendTo('#left-side');
//                    $('.ad-side').appendTo('#left-side')
//            }
//            else if($(window).width() <= 800) {
//                if($("#right-side:has('.news-contain')").length > 0)
//                    $('.news-container').appendTo('#right-side');
//                if($("#right-side:has('.ad-side)").length > 0)
//                    $('.ad-side').appendTo('#right-side');
//                
//            }
//        });
//    });
//    $('.news-container').appendTo('#left-side')
</script>