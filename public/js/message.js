$(() => {

    let createNotification = (length, array) => {

        data.badge = data.notif.find('.badge');
        if(data.badge.is(':empty')){
            data.badge.text(length);
        }else{
            data.badge.text(parseInt(data.badge.text()) + length);
        }

        if(Array.isArray(array)){

            array.map( item => {
                data.notifLi = $('<li class="dropdown-item text-primary form-group header-request">');
                data.text = item.name +  " send follow request. ";
                data.accept = createFollowButton('fa-check accept-follow',item.followerId);
                data.cancel = createFollowButton('fa-times cancel',item.followerId);
                data.notifLi.append(data.text)
                            .append(data.accept)
                            .append(data.cancel);
                data.notifMenu.append(data.notifLi);
                data.notif.append(data.notifMenu);
            });
        }
    };


    let responseMessage = () => {

        $.ajax({
            url     : '/notifications',
            method  : 'POST',
            data    : {
                'get_id' : data.get_id
            },
            success: res => {

                if(res.status === "success"){
                    if(res.followers){
                        createNotification(res.followers.length, res.followers);
                    }
                    if(res.info){
                        res.info.map( item => {
                            data.sendInput = $('.send-message');
                            data.message.show();
                            data.text = createMessage(item.message, item.date, "success", "left");
                            data.messageBody.append(data.text);
                            data.toFriendProfile =  "<a href='http://github.dev/user/"+ item.id +"' target='_blank' >"
                                + item.name +
                                "</a>";
                            data.userName.html(data.toFriendProfile);
                            data.sendInput.attr('data-id',item.id);
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
                data:{
                    'id' : data.get_id
                },
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

    $(document).on('keyup','.send-message', e => {

        data.this   =   $(e.target);
        data.val = data.this.val().trim();
        data.id  = data.this.data('id');

        if(event.keyCode == 13 && data.val != ""){
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
                        data.text = createMessage(data.val, res.date, "info", "right");
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

    setInterval(responseMessage,5000);
});