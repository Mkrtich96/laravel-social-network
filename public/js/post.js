$(() => {

    $(document).on('click','.post', function (e) {

        e.preventDefault();
        data.sendButton =   $(this);
        data.checked    =   data.form_post.find('.checkbox').is(':checked');
        data.post       =   data.form_post.find('.post-text').val().trim();

        if(data.post != ""){

            $('.alert-post-error').fadeOut();

            data.sendButton.removeClass('btn-primary')
                            .addClass('btn-secondary')
                            .prop('disabled',true)
                            .html("...");

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

                        $('.post-text').val("");

                        data.sendButton.removeClass('btn-secondary')
                            .addClass('btn-primary')
                            .prop('disabled',false)
                            .html("Post");

                        data.cards1     =   $('<div class="users-res card col-12 col-sm-12">');
                        data.cards2     =   $('<div class="card-body">');
                        data.cards3     =   $('<div class="card-text">');
                        data.cardTitle  =   $('<h5 class="card-title">');
                        data.avatar     =   $('<img src="'+ data.profile_photo +'" class="rounded-circle followers-avatar float-left">');
                        data.textDiv    =   $('<div class="card-text mt-3">');
                        data.textCon    =   $('<p class="text-justify">');
                        data.date       =   $('<small class="form-text text-muted">');

                        data.textCon.text(data.post);
                        data.textDiv.append(data.textCon);
                        data.cardTitle.text(data.profile_name);
                        data.date.text(res.date);

                        data.cards3.append(data.avatar)
                                    .append(data.cardTitle)
                                    .append(data.date);

                        data.cards2.append(data.cards3)
                                    .append(data.textDiv);

                        data.cards1.append(data.cards2);
                        data.cards.prepend(data.cards1);

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