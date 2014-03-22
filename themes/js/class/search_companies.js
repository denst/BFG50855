SearchCompanies = new function() {

    this.cookie = 'search_companies';
    this.params = null;

    this.removeParam = function(name) {
        delete this.params[name];
        $.cookie(this.cookie,$.toJSON(this.params),{expires : 7});
    }

    this.saveParam = function(name,value) {
        this.params[name] = value;
        $.cookie(this.cookie,$.toJSON(this.params),{expires : 7});
    }

    this.init = function() {

        var params = $.evalJSON($.cookie(this.cookie));
        if (params == null)
            params = {};

        this.params = params;

        $("#search-company").ajaxChosen({
            url: '/search/json_company',
            dataType: 'json',
            jsonTermKey: 'search'
        }, function (data) {

            var terms = {};

            $.each(data.items, function (i, val) {
                terms[i] = val;
            });

            return terms;
        }).change(function(){

            if ($(this).val() != '0')
                Navigation.loadPage('/company' + $(this).val());

        });

        $("#search-rubrick").ajaxChosen({
            url: '/search/json_itemrubrick',
            dataType: 'json',
            jsonTermKey: 'search'
        }, function (data) {

            var terms = {};

            $.each(data.items, function (i, val) {
                terms[i] = val;
            });

            return terms;
        }).change(function() {

            SearchCompanies.saveParam('category',$(this).val());
            Navigation.refreshPage();

        });

        $("#search-city").ajaxChosen({
            url: '/search/json_city',
            dataType: 'json',
            jsonTermKey: 'search'
        }, function (data) {

            var terms = {};

            $.each(data.items, function (i, val) {
                terms[i] = val;
            });

            return terms;
        }).change(function(){

            SearchCompanies.saveParam('city',$(this).val());
            Navigation.refreshPage();

        });

    }

}