$(() => {
    $(document).on('click','.post', function (e) {

        e.preventDefault();
        data.checked    =   data.form_post.find('.checkbox').is(':checked');
        data.post       =   data.form_post.find('.post-text').val().trim();
        console.log(data.post);
        if(data.post != ""){
            $('.alert-post-error').fadeOut();

            $.ajax({
                method  : "POST",
                url     : "/post",
                data    : {
                    "_token"    : data.token,
                    "get_id"    : data.get_id,
                    "status"    : data.checked,
                    'post'      : data.post
                },
                success : (res) => {

                    if(res.ok){
                        $('.alert-post-success').fadeIn();

                    }
                },
                statusCode : {
                    404 : () => {


                    }
                }
            });
        }else{
            $('.alert-post-error').fadeIn();


        }


    });

});