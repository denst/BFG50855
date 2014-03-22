addItem = {
    tree: function(element,url)
    {
        element = $(element);
        if (element.hasClass('category-tree'))
        {
            element.after(Navigation.imgLoading);

            $.ajax({
                url: url + element.attr('data-id'),
                dataType : 'html',
                success : function (data) {
                    $('.loading-process').remove();
                    //element.unbind('click');
                    element.removeClass('category-tree');
                    element.addClass('category-tree-open');
                    element.after(data);
                    $('#param-' + element.attr('data-id')).show();
                }
            });
        }
        else if (element.hasClass('category-tree-open'))
        {
            $('#parent-' + element.attr('data-id')).remove();
            element.removeClass('category-tree-open');
            element.addClass('category-tree');
            $('#param-' + element.attr('data-id')).hide();
        }
    },

    select: function(element)
    {
        element = $(element);
        var tree = $('#tree');

        $.ajax({
            url : '/items/category/' + element.attr('data-id'),
            dataType : 'html',
            success : function (data) {
                tree.after(data);
                tree.remove();
            }
        });
    }
}


