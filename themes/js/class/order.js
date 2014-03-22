// Класс заказа

Order = new function() {

	this.recalcItem = function(item_id) {
		var $count = $('input[name="item_count['+item_id+']"]');
		var count = Number($count.val());
		var price = $('#item_price_'+item_id).html();

		var count_on_store = $count.attr('validation-max');	// количество товара на складе производителя

		if (count < 0) count = 0;
		if (count > count_on_store) count = count_on_store;
		$count.val(count);

		$('#item_itogo_'+item_id).html((count * price));

		// Обновим сумму "Итого".
		var itogo_summ = 0;
		$('.item_itogo').each(function() {
			itogo_summ = itogo_summ + Number(this.innerHTML);
		});

		$('#itogo_summ').html(itogo_summ);
	};


    // Я не знаю, что творит этот код, потому что его писал Володя, и он его никак не откомментировал.
    // Я лишь знаю, что он как-то связан с созданием встречного предложения в заказах
    this.counter = function(elements)
    {

        $(elements).each(function() {

            var total = function() {
                var total = 0;

                table.find('tbody tr').each(function(){
                    var row=$(this);
                    var sum=parseInt($('#sum-'+row.attr('id')).text());
                    if(!isNaN(sum))
                        total+=sum;
                });

                $('#inTotal').text(total);

            };

            var calcRow = function(row) {
                var count=$('#count-'+row.attr('id')).children('input').val();
                var price=$('#price-'+row.attr('id')).children('input').val();
                var sum=parseInt(price)*parseInt(count);
                return sum;
            };

            var table = $(this);

            table.find('.del-row').unbind('click').bind('click',function() {
                $(this).parent().parent().remove();
                if(table.find('tbody tr').length==0){
                    $('#sendOferer').hide();
                }else{
                    $('#sendOferer').show();
                    total();
                }
            });

            table.find('input').unbind('keyup').bind('keyup',function(e) {
                var row=$(this).parent().parent();
                var sum=calcRow(row);
                if(isNaN(sum))
                    row.find('#sum-'+row.attr('id')).text('Введены не правильные данные');
                else
                    row.find('#sum-'+row.attr('id')).text(sum);
                total();

            });

        });

    }


}