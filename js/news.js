
$(document).ready(function(){
    $('.delete-news').on('click', function(e){
        var $link=$(this).attr('href');
        e.preventDefault();
        $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .one('click', '#delete', function (e) {
                window.location = $link;
            });
    });
});


