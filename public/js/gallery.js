$(function () {

    /**
     * Delete Picture
     */

    $(document).on('click','.delete-img',function () {
        let _this = $(this);
        data.id = $(this).data('id');
        data.src = $(this).parent().find('.gallery-img').attr('src');
        $.ajax({
            method  : "DELETE",
            url     : "/gallery/" + data.id,
            data    : {
                '_token'    : data.token,
                'src'       : data.src
            },
            success   : function (data) {
                if(data){
                    _this.parent().parent().remove();
                }
            }
        });
    });



    $(document).on("mousemove",'.images',function () {
        $(this).find('.delete-img').css({'display' : 'block'});
    });
    $(document).on("mouseout",'.images',function () {
        $(this).find('.delete-img').css({'display' : 'none'});
    })

});