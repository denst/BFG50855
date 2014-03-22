<h1>Удаление объявления №<a href="<?=$advert->link()?>"><?=$advert->id?></a></h1>
<p>Вы действительно желаете удалить это объявление?</p>
<div class="btn btn-primary senddata-token" data-link="/posts/delete/<?=$advert->id?>">Удалить объявление</div>
<a href="/posts/my" class="btn ml10px">Отмена</a>