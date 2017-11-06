$(() => {

    /**
     * Delete Picture
     */

    $(document).on('click','.delete-img', e => {
        data.this   = $(e.target);
        data.id     = data.this.data('id');
        data.src    = data.this.parent().find('.gallery-img').attr('src');

        $.ajax({

            method  : "DELETE",
            url     : "/gallery/" + data.id,
            data    : {
                'id'    :   data.id,
                'src'   :   data.src
            },
            success   : res => {

                if(res.status === "success"){
                    data.this.parents(".images").remove();
                }else{
                    console.error("Connection error!")
                }
            },
            statusCode : {
                404: res => {
                    arrangeResponse(res.responseJSON);
                },
                422: res => {
                    arrangeResponse(res.responseJSON[0], 422, "gallery delete");
                }
            }
        });
    });

    let showDeleteIcon = e => {

        $(e.target).parent().find('.delete-img').fadeIn();
    };

    $(document).on("mousemove",'.delete-img', showDeleteIcon);

    $(document).on("mousemove",'.gallery-img', showDeleteIcon);

    $(document).on("mouseout",'.gallery-img', e => {

        $(e.target).parent().find('.delete-img').fadeOut();
    })

});