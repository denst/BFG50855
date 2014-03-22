<div class="span3">
    <?= View::factory('columns/cabinet') ?>
</div>
<div class="span6 detail">
    <? if ($auth->logged_in() &&  $user->has('roles',ROLE_ADMIN)){ ?>
        <a class="btn btn-small manage fl_r" href="<?=$main_link?>/save/<?=$blog->id?>">Управление</a>
        <a class="mr5px btn btn-small really manage fl_r" href="<?=$main_link?>/delete/<?=$blog->id?>">Удалить</a>
        <div data-link="<?=$main_link?>/toggleshow/<?=$blog->id?>" class="mr5px btn btn-small fl_r senddata-token">Скрыть/Отобразить</div>

        <? if (!$blog->show) { ?>
        <h2>Запись скрыта от посетителей</h2>
        <? } ?>
    <? } ?>

    <? if (Request::current()->controller() != 'blog') { ?>
        <h1><a href="<?=$main_link?>"><?=$blog_name?></a></h1>
    <? } ?>

    <div class="fl_r"><?=Text::humanDate($blog->date)?></div>
    <h2><?=$blog->name?></h2>
    <? if (!empty($blog->photo_id)) { ?>
    <div class="avatar">
        <img style="width: 230px;" src="<?=$blog->photo->path()?>" alt="<?=$blog->name?>" />
    </div>
    <? } ?>
    <div class="mt15px fnt-normal">
        <?=$blog->text?>
    </div>
    <div class="clear"></div>
</div>
<div class="span3">
</div>