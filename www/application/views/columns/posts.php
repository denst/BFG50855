<!-- <div style="margin-top:30px;font-size: 12px;">
    <? if ($location->search == 'country') { ?>
        <? foreach(ORM::factory('country')->where('id','<>',$location->country->id)->find_all() as $country) { ?>
            <p><a href="http://realtynova.<?=$country->domain?><?=Request::current()->url()?>"><?=etc::postsName($cat,$type)?> в <?=$country->name_rp?></a></p>
        <? } ?>
    <? } ?>
    <? if ($location->search == 'region') { ?>
        <? foreach(ORM::factory('region')
                ->where('id','<>',$location->region->id)
                ->where('countries_id','=',$location->country->id)
                ->limit(10)
                ->find_all() as $region) { ?>
            <p style="margin:5px 0"><a href="http://<?=$region->domain?>.realtynova.<?=$location->country->domain?><?=Request::current()->url()?>"><?=etc::postsName($cat,$type)?> в <?=$region->name_rp?></a></p>
        <? } ?>
    <? } ?>
    <? if ($location->search == 'city') { ?>
        <? foreach(ORM::factory('city')
                ->where('id','<>',$location->city->id)
                ->where('regions_id','=',$location->region->id)
                ->limit(10)
                ->find_all() as $city) { ?>
            <p style="margin:5px 0"><a href="http://<?=$city->domain?>.realtynova.<?=$location->country->domain?><?=Request::current()->url()?>"><?=etc::postsName($cat,$type)?> в <?=$city->name_rp?></a></p>
        <? } ?>
    <? } ?>
</div> -->