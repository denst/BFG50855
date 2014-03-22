<h1>Удаление объявления №<a href="<?=$flat->advert->link()?>"><?=$flat->id?></a></h1>
<p>Вы действительно желаете удалить это объявление?</p>
<div class="btn btn-primary senddata-token" data-link="/advertisements_flats/delete/<?=$flat->id?>">Удалить объявление</div>
<a href="/advertisements/my" class="btn ml10px">Отмена</a>