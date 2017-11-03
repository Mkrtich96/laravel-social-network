$(() => {

    $(document).on('click','.post', e => {

        e.preventDefault();

        data.sendButton =   $(e.target);
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
                success : res => {

                    if(res.ok){

                        $('.post-text').val("");

                        data.sendButton.removeClass('btn-secondary')
                            .addClass('btn-primary')
                            .prop('disabled',false)
                            .html("Post");

                        data.cards1     =   $('<div>').addClass('users-res card col-12 col-sm-12');

                        data.cards2     =   $('<div>').addClass('card-body');
                        data.cards3     =   $('<div>').addClass('card-text');
                        data.commField  =   $('<div>').addClass('card-text');

                        data.commBtn    =   $('<a>').addClass('btn comment badge badge-primary text-light float-right')
                                                    .attr('data-id', res.post_id)
                                                    .html('Comments');

                        data.cardTitle  =   $('<h5>').addClass('card-title');

                        data.avatar     =   $('<img>').addClass('rounded-circle followers-avatar float-left')
                                                        .attr('src', data.profile_photo);

                        data.textDiv    =   $('<div>').addClass('card-text mt-3');

                        data.textCon    =   $('<p>').addClass('text-justify');

                        data.date       =   $('<small>').addClass('form-text text-muted');

                        data.textCon.text(data.post);
                        data.textDiv.append(data.textCon);
                        data.cardTitle.text(data.profile_name);
                        data.date.text(res.date);

                        data.commField.append(data.commBtn);

                        data.cards3.append(data.avatar)
                                    .append(data.cardTitle)
                                    .append(data.date);

                        data.cards2.append(data.cards3)
                                    .append(data.textDiv)
                                    .append(data.commField);

                        data.cards1.append(data.cards2).hide();
                        data.cards.prepend(data.cards1);
                        data.cards1.fadeIn();

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


    $(document).on('click', '.comment', e => {

        $(e.target).parents('.card-body').find('.comments-body').fadeToggle();

    });

    $(document).on('keyup', '.apply-comment',  e => {
        //data.this =


    })


});