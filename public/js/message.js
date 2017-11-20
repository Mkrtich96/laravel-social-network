$(() => {

    let createNotification = array => {

        data.badge = data.notif.find('.badge');
        console.log(data.badge);
        if(data.badge.is(':empty')){
            data.badge.text(array.length);
        }else{
            data.badge.text(parseInt(data.badge.text()) + array.length);
        }

        if(Array.isArray(array)){
            array.map( item => {
                console.log(item.data);
                switch (item.system){
                    case 'follow':  data.notifLi = $('<li class="dropdown-item text-primary form-group header-request">');
                                    data.text = `${item.data.follower_name} send follow request.`;
                                    data.accept = createFollowButton('fa-check accept-follow',item.data.follower_id);
                                    data.cancel = createFollowButton('fa-times cancel-follow',item.data.follower_id);
                                    data.notifLi.append(data.text)
                                                .append(data.accept)
                                                .append(data.cancel);
                                    data.notifMenu.append(data.notifLi);
                                    data.notif.append(data.notifMenu);
                                    break;
                    case 'comment': data.notifLi = $('<li class="dropdown-item text-primary form-group header-request">');
                                    data.text = `${item.data.commentator_name} applied on your comment.`;
                                    data.notifLi.append(data.text);
                                    data.notifMenu.append(data.notifLi);
                                    data.notif.append(data.notifMenu);
                                    break;
                    default: break;
                }
            });
        }
    };


    let responseMessage = () => {

        $.ajax({
            url     : '/notifications',
            method  : 'POST',
            success: res => {

                if(res.status === "success"){
                    if(res.notifications){

                        createNotification(res.notifications);
                    }

                    if(res.received_messages){
                        res.received_messages.map( message => {
                            data.sendInput = $('.send-message');
                            data.message.show();
                            data.text = createMessage(message.message, message.created_at, "success", "left");
                            data.messageBody.append(data.text);
                            data.toFriendProfile =  `<a href='http://github.dev/user/${message.user.id}' target='_blank' >
                                                        ${message.user.name}
                                                    </a>`;
                            data.userName.html(data.toFriendProfile);
                            data.sendInput.attr('data-id',message.id);
                            scrollDown(data.messageBody);
                        });
                    }
                    if(res.seen){
                        data.messageText    = data.messageBody.find('.message-text');
                        data.messageText.last().append(data.seenText);
                    }
                }
            },
            statusCode:{
                404: res => {
                    arrangeResponse(res.responseJSON)
                },
                422: res => {
                    arrangeResponse(res.responseJSON, 'fail', 422);
                }
            }
        })
    };

    $(document).on('click','.message', () => {
        data.messageText = data.messageBody.find('.message-text');
        if(data.messageText.last().hasClass('text-left')){
            $.ajax({
                url:    '/seen',
                method: 'POST',
                success: res => {

                    if(res.status === 'success'){
                        return true;
                    }else{
                        console.error("Error with (/seen) request. StatusCode 200.")
                    }
                },
                statusCode: {
                    404: res => {
                        arrangeResponse(res.responseJSON)
                    },
                    422: res => {
                        arrangeResponse(res.responseJSON, 'fail', 422)
                    }
                }
            })
        }
    });

    /**
     * Message Window
     */

    $(document).on('click','.open-message', e => {

        e.preventDefault();
        data.this   = $(e.target);
        data.name   = data.this.text();
        data.to     = data.this.data('id');
        data.avatar = data.this.parent().find('.followers-avatar');

        $.ajax({
            url:    '/select-messages',
            method: 'POST',
            data:   {
                'to': data.to
            },
            success: res => {
                if(res.status === 'success'){
                    data.message.fadeIn();
                    data.sendInput = $('.send-message');
                    data.xsUserAvatar = `<img class="rounded-circle xs-avatar" src="${data.avatar.attr('src')}">`;
                    data.toFriendProfile = `<a href="http://github.dev/user/${data.to}" target="_blank">${data.name}</a>`;
                    data.userName.html(`${data.xsUserAvatar} ${data.toFriendProfile}`);
                    data.sendInput.attr("data-id",data.to);

                    if(res.messages){
                        res.messages.map( item => {
                            if(item.from === data.get_id){
                                data.color      = "info";
                                data.position   = "right";
                            }else{
                                data.color      = "success";
                                data.position   = "left";
                            }
                            data.create = createMessage(item.message, item.created_at, data.color, data.position);
                            data.messageBody.append(data.create);
                            scrollDown(data.messageBody);
                        });
                    }
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
        });
    });

    /**
     * Close message window
     */
    $(document).on('click','.close', () => {
        data.messageBody.html("");
        data.message.fadeOut();
    });

    $(document).on('keyup','.send-message', e => {

        data.this   =   $(e.target);
        data.val = data.this.val().trim();
        data.id  = data.this.data('id');

        if(event.keyCode === 13 && data.val !== "" && !e.shiftKey){

            $.ajax({
                url : "/send",
                method : "POST",
                data : {
                    'message'   :   data.val,
                    'user_id'   :   data.id
                },
                success : res => {
                    if (res.status === "success") {
                        $('.send-message').val("");
                        data.messageText = data.messageBody.find('.message-text').last();

                        if (data.messageText.find('.cite').last().hasClass('last')) {
                            data.messageText.find('.cite').last().remove();
                        }
                        data.text = createMessage(res.date, "info", "right");
                        data.messageBody.append(data.text);
                        scrollDown(data.messageBody);
                    } else {
                        console.error("Message dones't sended route('/send'). StatusCode: 200");
                    }
                },
                statusCode : {
                    404 : res => {
                        arrangeResponse(res.responseJSON);
                    },
                    422 : res => {
                        arrangeResponse(res.responseJSON, 'fail', 422);
                    }
                }
            });
        }
        return false;
    });

    $(document).on('keyup','.conversation-message', e => {
        data.this   =   $(e.target);
        data.conversation_id = data.this.parents('.conversation').data('id');
        data.val = data.this.val().trim();
        data.id  = data.this.data('id');
        data.convers_mes_body = $('.convers-message-list');

        if(event.keyCode === 13 && data.val !== "" && !e.shiftKey){

            $.ajax({
                // conversationMessage method()
                url : "/conversation-message",
                method : "POST",
                data : {
                    'message': data.val,
                    'conversation_id': data.conversation_id
                },
                success : res => {
                    if (res.status === "success") {
                        data.this.val("");
                        data.text = createMessage(res.date, res.auth, "info", "right");
                        data.convers_mes_body.append(data.text);
                        scrollDown(data.convers_mes_body);
                    } else {
                        console.error("Message dones't sended route('/send'). StatusCode: 200");
                    }
                },
                statusCode : {
                    404 : res => {
                        arrangeResponse(res.responseJSON);
                    },
                    422 : res => {
                        arrangeResponse(res.responseJSON, 'fail', 422);
                    }
                }
            });
        }
    });

    if($('.convers-message-list').length){

        scrollDown($('.convers-message-list'));
    }

    setInterval(responseMessage,5000);
});