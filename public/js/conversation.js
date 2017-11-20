$(() => {

    let groupResponse = () => {

        data.message = $('.message-text').last();
        data.conversation = $('.group-id');
        data.conversation_id = data.conversation.data('id');
        data.convers_mes_body = $('.convers-message-list');
        console.log(data.message.data('id'));

        $.ajax({
            url: '/select-group-messages',
            method: "POST",
            data: {
                'id': data.conversation_id,
                'message': data.message.data('id')
            },
            success: res => {
                if(res.status === 'success'){

                    data.message = createMessage(res.group_messages, res.group_messages.user, "success", "left");
                    data.convers_mes_body.append(data.message);
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