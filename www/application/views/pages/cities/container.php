<? $countries = ORM::factory('country')->find_all(); ?>
<? /*
<div id="countries-list">
<? foreach($countries as $country) { ?>
    <div style="float:left;width:25%;">
        <h1 onmouseover="setActiveCountry('<?=$country->id?>')" class="country-<?=$country->id?><?if ($country->id == $location->country->id) { ?> active<?}?>"><a target="_blank" href="http://realtynova.<?=$country->domain?>"><?=$country->name?></a></h1>
    </div>            
<? } ?>
</div> 
*/ ?>
<? foreach($countries as $country) { ?>
    <?
        $columns = 4;
        $column = 0;
        $regions = $country->regions->order_by('name')->find_all();
        $active = ($country->id == $location->country->id);
        $column_width = str_replace(',','.',(100-$columns+1)/$columns);
        if ($active) {
    ?>
    <div id="regions-<?=$country->id?>" class="regions<?=$active ? ' active' : ''?>">
        <div class="regions-column" style="width: <?=$column_width?>%">
        <? foreach($regions as $region) { ?>
            <? $column += 1; if ($column > ($regions->count() / $columns)) { $column = 0; ?> </div> <div class="regions-column" style="width: <?=$column_width?>%"> <? } ?>
                <div class="region">
                    <p><a href="http://<?=$region->domain?>.realtynova.<?=$country->domain?>"><?=$region->name?></a></p>
                    <div class="cities" style="display: none">
                        <? foreach($region->cities->order_by('name')->find_all() as $city) { ?>
                            <a class="city" href="http://<?=$city->domain?>.realtynova.<?=$country->domain?>"><?=$city->name?></a>
                        <? } ?>
                    </div>
                </div>
        <? } ?>
        </div>
        <div class="clear"></div>
    </div>
<? } } ?>
