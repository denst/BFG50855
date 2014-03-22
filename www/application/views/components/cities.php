<div style="display: none" id="cities-background" onclick="$('#cities-container,#cities-background').hide();"></div>
<div style="display: none" id="cities-container"></div>

<script type="text/javascript">
    function showcitiescontainer() {
        if ($('#cities-container').html() == '') {
            $.ajax({
                url:'/cities/container',
                success: function(data) {
                    $('#cities-container').html(data);
                    
//                    $('.country > p').mouseover(function(){
//                        if ($('.regions:visible',$(this).parent()).length == 0)
//                            $('.regions',$(this).parent()).show('normal');
//                    });

                    $('.region > p').mouseover(function(){
                        if ($('.cities:visible',$(this).parent()).length == 0) {
                            $('#cities-container .cities').stop().hide('normal');
                            $('.cities',$(this).parent()).stop().show('normal');
                        }
                    });
                    
                    $('#cities-container,#cities-background').show();
                }
            });                
        } else {
            $('#cities-container,#cities-background').show();
        }
    }
</script>