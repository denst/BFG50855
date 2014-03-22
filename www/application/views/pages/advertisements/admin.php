<ul id="tabs">
    <li><a href="#" name="#tab1">Квартиры</a></li>
</ul>
<div id="content">
    <div id="loadcontent-container"></div>
    <div id="tab1" class="tab">
        <?= Request::factory('/advertisements_flats')->execute()->body()?>
    </div>
</div>
