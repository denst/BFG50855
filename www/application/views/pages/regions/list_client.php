<table style="width: 100%;">
    <tr>
        <? foreach($countries as $country) { ?>
            <td style="vertical-align: top">
                <h1 style="font-size: 30px;"><?=$country->name?></h1>
                <? foreach($country->regions->order_by('name')->find_all() as $region) { ?>
                    <h2 style="font-size: 20px;"><a href="http://<?=$region->domain?>.realtynova.ru"><?=$region->name?></a></h2>
                    <? foreach($region->cities->order_by('name')->find_all() as $city) { ?>
                        <p><a href="http://<?=$city->domain?>.realtynova.ru"><?=$city->name?></a></p>
                    <? } ?>
                <? } ?>
            </td>
        <? } ?>
    </tr>
</table>
