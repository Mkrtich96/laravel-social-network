$(() => {

    let groupResponse = () => {
        data.conversation = $('.group-id');
        data.conversation_id = data.conversation.data('id');
        data.convers_mes_body = $('.convers-message-list');

        $.ajax({
            url: '/select-group-messages',
            method: "POST",
            data: {
                'id' : data.conversation_id
            },
            success: res => {
                if(res.status === 'success'){
                    console.log(res);
                    res.group_messages.map(item => {
                        data.message = createMessage(item.message, item.created_at, item.user, "success", "left");
                        data.convers_mes_body.append(data.message);
                    });
                    scrollDown(data.convers_mes_body);
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

    data.location = location.pathname.split('/');

    if(data.location[1] === 'group'){

        setInterval(groupResponse, 5000)
    }

});