$(() => {

    /**
     * Delete Picture
     */

    $(document).on('click','.delete-img', e => {
        data.this = $(e.target);
        data.id = data.this.data('id');
        data.src = data.this.parent().find('.gallery-img').attr('src');
        $.ajax({
            method  : "DELETE",
            url     : "/gallery/" + data.id,
            data    : {
                'src'       : data.src
            },
            success   : data => {

                if(data.ok){
                    data.this.parent().parent().remove();
                }
            },
            statusCode : {
                404: () => {
                    console.log('Gallery delete response not found. Error 404.');
                }
            }
        });
    });



    $(document).on("mousemove",'.images', e => {
        $(e.target).find('.delete-img').css({'display' : 'block'});
    });

    $(document).on("mouseout",'.images', e => {
        $(e.target).find('.delete-img').css({'display' : 'none'});
    })

});