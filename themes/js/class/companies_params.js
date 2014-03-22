var CompaniesParams = {
    init: function() {
        
        $('#params-phones .addphone').bind('click',function() {
            var id = 0;
            $('#params-phones table tbody tr').each(function(){
                if (this.id > id) id = this.id;
            });

            $.tmpl($('#tmpl-params-phones'),{'id':+id+1}).appendTo($('#params-phones tbody'));
            $('#params-phones table').tableDnD();
            return false;
        });

        $('#params-emails .addemail').bind('click',function() {
            var id = 0;
            $('#params-emails table tbody tr').each(function(){
                if (this.id > id) id = this.id;
            });

            $.tmpl($('#tmpl-params-emails'),{'id':+id+1}).appendTo($('#params-emails tbody'));
            $('#params-emails table').tableDnD();
            return false;
        });
    }
}