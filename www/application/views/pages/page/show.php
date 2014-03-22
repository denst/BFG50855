<div class="span3">
    <?= View::factory('columns/cabinet') ?>
</div>
<div class="span6 detail">
    <? if ($auth->logged_in() && $user->has('roles',ROLE_ADMIN)) { ?>
        <a class="btn fl_r" href="/page/save/<?=$page->id?>">Редактировать</a>
    <? } ?>
    <h1><?=$page->name?></h1>
    <div class="fnt-normal">
        <?=$page->text?>
    </div>
</div>
<div class="span3">
</div>

