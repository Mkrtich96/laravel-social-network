$(() => {


    $(document).on('click','.post', e => {

        e.preventDefault();

        data.sendButton =   $(e.target);
        data.checked    =   data.form_post.find('.checkbox').is(':checked');
        data.post       =   data.form_post.find('.post-text').val().trim();

        if(data.checked){
            data.checked = 1;
        }else{
            data.checked = 0;
        }

        if(data.post !== ""){

            $('.alert-post-error').fadeOut();

            data.sendButton.removeClass('btn-primary')
                            .addClass('btn-secondary')
                            .prop('disabled',true)
                            .html("...");

            $.ajax({

                method  : "POST",
                url     : "/post",
                data    : {
                    "user_id"    : data.get_id,
                    "status"    : data.checked,
                    "text"      : data.post
                },
                success : res => {

                    if(res.status === "success"){

                        $('.post-text').val("");
                        data.sendButton.removeClass('btn-secondary')
                                        .addClass('btn-primary')
                                        .prop('disabled',false)
                                        .html("Post");

                        data.cards1     =   $('<div>').addClass('parent-card users-res card col-12 col-sm-12');
                        data.cards2     =   $('<div>').addClass('card-body');
                        data.cards3     =   $('<div>').addClass('card-text');
                        data.commInFil  =   $('<div>').addClass('card-text float-right w-75 comments-body');
                        data.commApply  =   $('<div>').addClass('input-group input-group-sm mt-2 apply-comment');
                        data.clearfix   =   $('<div>').addClass('clearfix');
                        data.commInput  =   $('<input>').attr({
                                                            "type"              : "text",
                                                            "placeholder"       : "Comment..",
                                                            "aria-describedby"  : "sizing-addon2"
                                                        }).addClass('rounded-0 form-control');

                        data.commField  =   $('<div>').addClass('card-text mt-3');
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
                        data.commApply.append(data.commInput);
                        data.commInFil.append(data.commApply);

                        data.cards3.append(data.avatar)
                                    .append(data.cardTitle)
                                    .append(data.date);

                        data.cards2.append(data.cards3)
                                    .append(data.textDiv)
                                    .append(data.commInFil)
                                    .append(data.clearfix)
                                    .append(data.commField);

                        data.cards1.append(data.cards2).hide();
                        data.cards.prepend(data.cards1);
                        data.cards1.fadeIn();

                        $('.alert-post-success').fadeIn();
                    }else{
                        console.error("Invalid post response! Connection error.");
                    }
                },
                statusCode : {
                    404 : res =>  {

                        arrangeResponse(res.responseJSON);
                    },
                    422 : res =>  {

                        arrangeResponse(res.responseJSON, 422, "post");
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

        data.this       =   $(e.target);
        data.value      =   data.this.val();
        data.post_id    =   data.this.data('id');
        data.user_id    =   data.this.data('user');
        data.parent_id  =   data.this.attr('parent-id');

        if(e.keyCode === 13){

            if(data.value !== ""){

                $.ajax({
                    url     :   "/comment",
                    method  :   "POST",
                    data    :   {
                        "comment"   :   data.value.trim(),
                        "post_id"   :   data.post_id,
                        "user_id"   :   data.user_id,
                        "parent_id" :   data.parent_id
                    },
                    success :   res => {
                        if(res.status === "success"){
                            console.log(res);
                            data.commentable = false;

                            if(res.comment_to){

                                console.log(res.comment_to);
                                data.to = $('<a>').attr('href', 'http://github.dev/user/' + res.comment_to.id).text(res.comment_to.name+" ");
                                data.commentable = true;
                            }

                            data.this.val("");
                            data.this.removeAttr('parent-id');
                            data.card = $('<div>').addClass('card');
                            data.card_body = $('<div>').addClass('card-body p-2');
                            data.card_title = $('<h5>').addClass('card-title');
                            data.card_title.text(res.commentator.name);

                            if(data.commentable){
                                data.card_body.append(data.card_title)
                                                .append(data.to)
                                                .append(res.comment);
                            }else{
                                data.card_body.append(data.card_title).append(res.comment);
                            }
                            data.card.append(data.card_body);
                            data.this.parent().before(data.card);

                        }
                    },
                    statusCode: {
                        404 : res => {
                            arrangeResponse(res.responseJSON);
                        },
                        422 : res => {
                            arrangeResponse(res.responseJSON, 'fail', 422);
                        }
                    }
                })
            }
        }
    });

    $(document).on('click', '.reply-comment', e => {

        e.preventDefault();
        data.this = $(e.target);
        data.comment_id = data.this.data('id');
        data.replyCommentTo = data.this.parent().find('.card-title');
        data.applyComment = data.this.parents('.comments-body').find('.send-comment');
        data.applyComment.attr('parent-id', data.comment_id)
                            .focus()
                            .val(data.replyCommentTo.text().trim());
    });

    $(document).on('click', '.comment-seen', e => {
        data.this = $(e.target);
        data.commentator_id = data.this.data('id');
        data.parent_id = data.this.attr('parent-id');

        $.ajax({
            url: '/comment-seen',
            method: "POST",
            data: {
                'notifiable_id' : data.parent_id,
                'to' : data.commentator_id,
                'system' : 'comment'
            },
            success: res => {
                if(res.status === 'success'){

                }else{
                    console.error('Comment seen response not success.');
                }
            },
            statusCode: {
                404: res => {
                    arrangeResponse(res.responseJSON);
                },
                422: res => {
                    arrangeResponse(res.responseJSON, 'fail', 422);
                }
            }

        })

    });


});