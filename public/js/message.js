$(function () {

    let createNotification = (length, array) => {
        data.badge = data.notif.find('.badge');
        if(data.badge.is(':empty')){
            data.badge.text(length);
        }else{
            data.badge.text(parseInt(data.badge.text()) + length);
        }
        if(Array.isArray(array)){
            array.map((item)=>{
                data.notifLi = $('<li class="dropdown-item text-primary form-group header-request">');
                data.text = item.name +  " send follow request. ";
                data.accept = createFollowButton('fa-check check',item.followerId);
                data.cancel = createFollowButton('fa-times cancel',item.followerId);
                data.notifLi.append(data.text).append(data.accept).append(data.cancel);
                data.notifMenu.append(data.notifLi);
                data.notif.append(data.notifMenu);
            });
        }
    };


    let responseMessage = () => {
        $.post('/generate_message/' + data.get_id, {
            '_token' : data.token
        },(res) => {
            if(res.message){
                createNotification(res.message.length, res.message);
            }
            if(res.info){
                data.sendInput = $('.send-message');
                data.message.css('display','block');
                data.text = createMessage(res.info.message, res.info.date, "success", "left");
                data.messageBody.append(data.text);
                data.toFriendProfile = "<a href='http://github.dev/user/"+ res.info.id +"' target='_blank' >" + res.info.name + "</a>";
                data.userName.html(data.toFriendProfile);
                data.sendInput.attr('data-id',res.info.id);
                scrollDown(data.messageBody);
            }else if(res == 1){
                data.messageText    = data.messageBody.find('.message-text');
                data.messageText.last().append(data.seenText);
            }
        })
    };

    $(document).on('click','.message',function () {
        data.messageText = data.messageBody.find('.message-text');
        if(data.messageText.last().hasClass('text-left')){
            $.post('/seen', {
                '_token'    : data.token,
                'id'        : data.get_id
            },(res) => {

            })
        }
    });

    $(document).on('keyup','.send-message',function (event) {
        data.val = $(this).val().trim().replace(/(<([^>]+)>)/ig,"");
        data.id  = $(this).data('id');
        if(event.keyCode == 13 && data.val != ""){
            $.post("/send/" + data.id,{
                '_token'    :   data.token,
                'message'   :   data.val
            },(res) => {
                if(res.ok){
                    $('.send-message').val("");
                    data.messageText    = data.messageBody.find('.message-text').last();

                    if(data.messageText.find('.cite').last().hasClass('last')){
                        data.messageText.find('.cite').last().remove();
                    }
                    data.text = createMessage(data.val, res.date, "info", "right");
                    data.messageBody.append(data.text);
                    scrollDown(data.messageBody);
                }
            });
        }
    });

    setInterval(responseMessage,5000);
});