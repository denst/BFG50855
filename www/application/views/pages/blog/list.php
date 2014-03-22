<h1><?=$blog_name?></h1>

<? if ($auth->logged_in() &&  $user->has('roles',ROLE_ADMIN)) { ?>
    <a class="btn" href="<?=$main_link?>/save">Новая запись</a>
    <div class="clear"></div>
<? } ?>

<?=$pager?>
<div id="blogs">
<? foreach($blogs as $blog) { ?>
    <div class="blog<? if (!$blog->show) { ?> notshow<? } ?>">
        
        <? if ($auth->logged_in() &&  $user->has('roles',ROLE_ADMIN)) {?>

            <a class="btn btn-small manage fl_r" href="<?=$main_link?>/save/<?=$blog->id?>">Управление</a>
            <a class="mr5px really senddata-token btn btn-small manage fl_r" href="<?=$main_link?>/delete/<?=$blog->id?>">Удалить</a>
        <? } ?>
        <div class="avatar">
            <a href="<?=$item_link . $blog->id?>"><img style="width: 230px;" src="<?=$blog->photo->thumb()?>" alt="<?=$blog->name?>" /></a>
        </div>
            <div class="title"><a href="<?=$item_link . $blog->id?>"><?=$blog->name?></a></div>
        <div class="text"><?=Text::limit_chars(strip_tags($blog->text),300,'...')?></div>
        <div class="clear"></div>
    </div>
<? } ?>
    <div class="clear"></div>
</div>
<?=$pager?>

<? if ($blogs->count() == 0) { ?>
    <p>Извините, но в данный момент у нас нет ни одной записи.</p>
<? } ?>




