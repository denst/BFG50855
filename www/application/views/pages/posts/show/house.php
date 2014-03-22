<? $flat = $advert->room; ?>
<table>
    <tbody><tr class="tittle"><td colspan="2">Описание дома</td></tr>
        <tr>
            <td>Материал:</td>
            <td><?=$flat->type->name?></td>
        </tr>
        <tr>
            <td>Тип:</td>
            <td><?=$flat->construction->name?></td>
        </tr>
        <tr>
            <td>Этажность:</td>
            <td><?=Text::chislitelnie($flat->floors,array('этаж','этажа','этажей'))?></td>
        </tr>
        <tr>
            <td>Лифт:</td>
            <td><?=($flat->lift == 0) ? 'Нет' : ($flat->lift == 1) ? 'Пассажирский' : 'Грузовой'?></td>
        </tr>
    </tbody>
</table>
<table>
    <tbody><tr class="tittle"><td colspan="2">Информация о квартире</td></tr>
        <tr>
            <td>Количество комнат:</td>
            <td><?=$flat->rooms?></td>
        </tr>
        <tr>
            <td>Площадь комнат:</td>
            <td><?=$flat->advert->square?></td>
        </tr>
        <tr>
            <td>Этаж:</td>
            <td><?=$flat->floor?></td>
        </tr>
        <tr>
            <td>Высота потолков:</td>
            <td><?=$flat->ceilingheight?> м</td>
        </tr>
        <tr>
            <td>Пол:</td>
            <td><?=$flat->pol->name?></td>
        </tr>
        <tr>
            <td>Санузел:</td>
            <td><?=$flat->wc->name?></td>
        </tr>
        <tr>
            <td>Телефон:</td>
            <td><?=($flat->phone == 0) ? 'Нет' : 'Есть'?></td>
        </tr>

    </tbody>
</table>
