$(function () {

    data = {
        "token"         : $('meta[name="csrf-token"]').attr('content'),
        "cards"         : $('.cards'),
        "cards1"        : $('<div class="card col-12 col-sm-12">'),
        "cards2"        : $('<div class="card-body">'),
        "cards3"        : $('<div class="card-text">'),
        "cardTitle"     : $('<h4 class="card-title">'),
        "button"        : $('<button>'),
        "lists"         : $('.list-group-item'),
        "dropdowns"     : $('.dropdowns'),
        "menu"          : $('.dropdown-menu'),
        "input"         : $('<input>'),
        "search"        : $(".search-input"),
        "followers"     : $(".list-group"),
        "message"       : $('.message'),
        "userName"      : $('.user-name'),
        'get_id'        : $('.get-id').data('id'),
        'images'        : $('.gallery-img'),
        'row'           : $('.images'),
        'cite'          : $("<cite>"),
        'messageBody'   : $('.anyClass'),
        'citeElements'  : $('.cite'),
        'seenText'      : "<cite class='cite last'>  Seen!</cite>",
        'notifMenu'     : $('<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">'),
        'notif'         : $('li.dropdown'),


};


    let searchRes = null;
    /**
     * Search autocompleate
     */
    data.search.autocomplete({
        source : "/search",
        response : function (event,ui) {
            searchRes = ui.content;
        }
    });

    $(document).on('click',".check",function (e) {
        e.preventDefault();

        data.parent         = $(this).parent();
        data.follower_id    = $(this).data('id');
        $.ajax({
            method : "POST",
            url    : "/accept",
            data   : {
                "_token" : data.token,
                "follower_id": data.follower_id
            },
            success : function (response) {
                if(response.ok){
                    data.unfllwBtn = $("<a class='btn btn-secondary float-right unfollow'>").html("Unfollow");
                    data.crtFollow = $("<li class='list-group-item'>");
                    data.badge = data.dropdowns.find(".badge-danger");
                    data.countNot = parseInt(data.dropdowns.find(".badge-danger").text());
                    if(data.countNot > 1){
                        data.badge.text(data.countNot - 1);
                    }else{
                        data.badge.remove();
                    }
                    data.parent.html("Request accepted!");
                    data.unfllwBtn.attr("data-id",response.id);
                    data.avatar = "<img src='http://github.dev/images/"+ data.follower_id +"/"+ response.avatar +"' class='rounded-circle followers-avatar'>";
                    data.crtFollow.append(data.avatar)
                                    .append("<a class='open-message text-primary' data-id='"+data.follower_id+"'>"+response.name+"</a>")
                                    .append(data.unfllwBtn);
                    data.followers.prepend(data.crtFollow);
                    setTimeout(function () {
                        "use strict";
                        data.parent.remove();
                        data.menu.remove();
                    },3000);
                }
            },
            statusCode: {
                404: function() {
                    console.log('Accept follow response not found. Error 404.');
                }
            }
        });
    });


    $(document).on('click',".cancel",function (e) {
        e.preventDefault();
        data.button = $(this);
        data.parent = $(this).parent();
        data.follower_id = $(this).data('id');
        $.ajax({
            method : "POST",
            url    : "/cancel",
            data   : {
                "_token" : data.token,
                "check"  : (data.parent.hasClass('header-request')) ? 1 : 0,
                "follower_id" : data.follower_id
            },
            success : function (response) {
                if(response.ok){
                    if(data.parent.hasClass('header-request')){
                        data.badge      = data.dropdowns.find(".badge-danger");
                        data.countNot   = parseInt(data.dropdowns.find(".badge-danger").text());
                        if(data.countNot > 1){
                            data.badge.text(data.countNot - 1);
                        }else{
                            data.badge.parent().attr('disabled',true);
                            data.badge.remove();
                        }

                        data.parent.html("Request canceled!");
                        setTimeout(function () {
                            "use strict";
                            data.parent.remove();
                            data.menu.remove();
                        },3000)
                    }else{
                        data.button.removeClass()
                            .addClass('btn btn-outline-primary follow')
                            .attr("data-id",response.id)
                            .html("Follow");
                    }
                }
            },
            statusCode: {
                404: function() {
                    console.log('Cancel follow response not found. Error 404.');
                }
            }
        })
    });

    $(document).on('click',".unfollow",function (e) {
        e.preventDefault();
        data.button = $(this);
        data.parent = $(this).parent();
        data.follower_id = $(this).data('id');
        $.ajax({
            method : "POST",
            url    : "/unfollow",
            data   : {
                "_token" : data.token,
                "follower_id" : data.follower_id,
            },
            success : function (response) {
                if(response.ok){
                        if(data.parent.hasClass('list-group-item')){
                            data.parent.remove();
                        }else{
                            data.button.removeClass('btn-primary unfollow').addClass('btn-outline-primary follow')
                                .attr("data-id",response.id)
                                .html("Follow");
                            $('.list-group-item').find("[data-id="+data.follower_id+"]").parent().remove();
                        }
                }
            },
            statusCode: {
                404: function() {
                    console.log('Unfollow response not found. Error 404.');
                }
            }
        });
    });

    $(document).on('click',".follow",function (e) {
        e.preventDefault();
        data.followBtn   = $(this);
        data.follower_id = $(this).data('id');
        $.ajax({
            method : "POST",
            url    : "/follow",
            data   : {
                "_token" : data.token,
                "follower_id" : data.follower_id,
            },
            success : (response) => {
                if(response.ok){
                    data.followBtn.removeClass('btn-outline-primary follow')
                                    .attr("data-id",response.id)
                                    .addClass('btn-secondary cancel')
                                    .html("Cancel Request");
                }
            },
            statusCode: {
                404: function() {
                    console.log('Follow response not found. Error 404.');
                }
            }
        })

    });

    $(document).on("click",".search-btn",function (e) {
        e.preventDefault();
        if(!$.isEmptyObject(searchRes)){
            data.cards.html("");

            searchRes.map((item) => {
                data.cards1     = $('<div class="card col-12 col-sm-12">');
                data.cards2     = $('<div class="card-body">');
                data.cards3     = $('<div class="card-text">');
                data.cardTitle  = $('<h4 class="card-title">');
                data.avatar     = $('<img src="'+ item.avatar +'" class="search-avatar rounded float-left">');
                data.button     = $('<button>');
                data.textDiv    = $('<div class="card-text">');
                data.cardTitle.html('<a href="http://github.dev/user/'+ item.id +'" target="_blank">' +item.value + '</a>');
                if (item.follow) {
                    data.button.addClass('btn btn-secondary unfollow')
                        .attr("data-id", item.id)
                        .html("Unfollow");
                } else if (item.requested){
                    data.button.removeClass('btn-outline-primary follow')
                        .attr("data-id", item.id)
                        .addClass('btn btn-secondary cancel')
                        .html("Cancel Request");
                } else {
                    data.button.addClass('btn btn-outline-primary follow')
                        .attr("data-id", item.id)
                        .html("Follow");
                }
                data.textDiv.append(data.button);
                data.cards3.append(data.avatar);
                data.cards3.append(data.cardTitle);
                data.cards3.append(data.textDiv);
                data.cards2.append(data.cards3);
                data.cards1.append(data.cards2);
                data.cards.append(data.cards1);

            });
        }
    });

    /**
     * Message Window
     */
    createMessage = (value, date, color, sender)  => {

        data.li     =   "<li class='list-group-item list-group-item-"+ color +" text-"+ sender +" message-text'>"
                            + value +
                            "<br>" +
                            "<cite class='cite' title='" + date + "'>" + date + "</cite>" +
                        "</li>";
        return data.li;
    };



    scrollDown = (element) => {

        return element.scrollTop(element[0].scrollHeight);
    };

    createFollowButton = (clasS,data_id) => {
        "use strict";
        return $('<a class="fa '+ clasS +' text-right">').attr('data-id',data_id);
    };

    $(document).on('click','.open-message',function (e) {
        e.preventDefault();
        data.to     = $(this).data('id');
        data.name   = $(this).text();
        data.avatar = $(this).parent().find('.followers-avatar');
        $.post('/selmessages',{
            '_token': data.token,
            'from'  : data.get_id,
            'to'    : data.to
        },(res) => {
            data.message.css('display','block');
            data.sendInput  = $('.send-message');
            data.xsUserAvatar = "<img src='"+ data.avatar.attr('src') +"' class='rounded-circle xs-avatar'>";
            data.toFriendProfile    = data.xsUserAvatar + "<a href='http://github.dev/user/"+ data.to +"' target='_blank' >" + data.name + "</a>";
            data.userName.html(data.toFriendProfile);
            data.sendInput.attr("data-id",data.to);
            data.messageBody.html("");
            res.item.map((item) => {
                if(item.from == data.get_id){
                    data.color      = "info";
                    data.position   = "right";
                }else{
                    data.color      = "success";
                    data.position   = "left";
                }
                data.date   = item.date.date.toString();
                data.date   = data.date.substring(0,19);
                data.create = createMessage(item.message, data.date, data.color, data.position);
                data.messageBody.append(data.create);
                scrollDown(data.messageBody);
            });
            if(res.seen == 1){
                data.messageText    = data.messageBody.find('.message-text').last();
                if(data.messageText.hasClass('text-right')){
                    data.messageText.find('.cite').last().append(data.seenText)
                }
            }

        });
    });


    /**
     * Close message window
     */
    $(document).on('click','.close', function() {

        data.message.css('display','none');
    });


});


