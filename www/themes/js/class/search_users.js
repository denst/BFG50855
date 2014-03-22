SearchUsers = new function() {

    this.cookie = 'search_users';
    this.params = null;

    this.removeParam = function(name) {
        delete this.params[name];
        $.cookie(this.cookie,$.toJSON(this.params),{expires : 1});
    }

    this.saveParam = function(name,value) {
        this.params[name] = value;
        $.cookie(this.cookie,$.toJSON(this.params),{expires : 1});
    }

    this.init = function() {

        var params = $.evalJSON($.cookie(this.cookie));
        if (params == null)
            params = {};

        this.params = params;

        $("#search-user").ajaxChosen({
            url: '/search/json_user_global',
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
                Navigation.loadPage('/id' + $(this).val());

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

            SearchUsers.saveParam('city',$(this).val());
            Navigation.refreshPage();

        });

        $("#search-profession").keyup(function(e){

            if (e.keyCode == '13')
            {
                SearchUsers.saveParam('profession',$(this).val());
                Navigation.refreshPage();
            }

        });

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

            SearchUsers.saveParam('company',$(this).val());
            Navigation.refreshPage();

        });

   }
}