<? $commerce = $advert->commerce; ?>
<table>
    <tbody><tr class="tittle"><td colspan="2">Описание коммерческой недвижимости</td></tr>
        <tr>
            <td>Тип:</td>
            <td><?=$commerce->type->name?></td>
        </tr>
        <tr>
            <td>Уровень:</td>
            <td><?=$commerce->property->name?></td>
        </tr>
    </tbody>
</table>