$(() => {

    data = {
        "token"         : $('meta[name="csrf-token"]').attr('content'),
        "cards"         : $('.cards'),
        "search_win"    : $('.modal').find('.modal-body'),
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
        'form_post'     : $('.form-post'),
        'profile_photo' : $('.card-img-top').attr('src'),
        'profile_name'  : $('.navbar-brand').text(),
    };




    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': data.token
        }
    });

    let searchRes = null;
    /**
     * Search autocompleate
     */
    data.search.autocomplete({
        source : "/search",
        response : (event,ui) => {
            searchRes = ui.content;
        }
    });

    $(document).on('click',".check", e => {

        e.preventDefault();
        data.this           =   $(e.target);
        data.parent         =   data.this.parent();
        data.follower_id    =   data.this.data('id');

        $.ajax({
            method : "POST",
            url    : "/accept",
            data   : {
                "follower_id": data.follower_id
            },
            success : response => {
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
                    setTimeout( () => {
                        "use strict";
                        data.parent.remove();
                        data.menu.remove();
                    },3000);
                }
            },
            statusCode: {
                404: () => {
                    console.log('Accept follow response not found. Error 404.');
                }
            }
        })
    });


    $(document).on('click',".cancel", e => {

        e.preventDefault();
        data.button = $(e.target);
        data.parent = $(e.target).parent();
        data.follower_id = $(e.target).data('id');

        $.ajax({
            method : "POST",
            url    : "/cancel",
            data   : {
                "check"  : (data.parent.hasClass('header-request')) ? 1 : 0,
                "follower_id" : data.follower_id
            },
            success : response => {

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
                404:    ()  =>  {
                    console.log('Cancel follow response not found. Error 404.');
                },
                422:    ()  =>  {
                    "use strict";

                }
            }
        })
    });

    $(document).on('click',".unfollow", e => {

        e.preventDefault();
        data.button = $(e.target);
        data.parent = $(e.target).parent();
        data.follower_id = $(e.target).data('id');

        $.ajax({
            method : "POST",
            url    : "/unfollow",
            data   : {
                "follower_id" : data.follower_id,
            },
            success : response => {
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
                404:    ()  => {
                    console.log('Unfollow response not found. Error 404.');
                },
                422:    ()  =>  {
                    "use strict";

                }
            }
        });
    });

    $(document).on('click',".follow", e => {

        e.preventDefault();
        data.this           = $(e.target);
        data.notifiable_id    = data.this.data('id');

        $.ajax({
            method : "POST",
            url    : "/follow",
            data   : {
                "notifiable_id" : data.notifiable_id,
            },
            success : response => {
                console.log(response.status);
                if(response.status == "success"){
                    data.this.removeClass('btn-outline-primary follow')
                                    .attr("data-id",response.id)
                                    .addClass('btn-secondary cancel')
                                    .html("Cancel Request");
                }
            },
            statusCode: {
                404: res  =>  {
                    try{
                        throw res.responseJSON.message;
                    }catch(err){
                        console.log(err);
                    }
                },
                422: res  =>  {
                    try{
                        throw res.responseJSON.message;

                    }catch(err) {
                        console.log(err);
                    }
                }
            }
        })

    });

    $(document).on("click",".search-btn", e => {

        e.preventDefault();

        if(!$.isEmptyObject(searchRes)){
            $('.modal').modal('show');
            data.search_win.html("");
            searchRes.map( item => {

                data.cards1     = $('<div>').addClass('users-res card col-12 col-sm-12');
                data.cards2     = $('<div>').addClass('card-body');
                data.cards3     = $('<div>').addClass('card-text');
                data.cardTitle  = $('<h4>').addClass('card-title');
                data.avatar     = $('<img>').addClass('search-avatar rounded float-left')
                                            .attr('src', item.avatar);
                data.button     = $('<button>');
                data.textDiv    = $('<div>').addClass('card-text');
                data.cardLink   = $('<a>').attr({
                                        'href' : "http://github.dev/user/"+ item.id,
                                        'target' : '_blank'
                                    }).html(item.value);
                data.cardTitle.append(data.cardLink);

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
                data.search_win.append(data.cards1);

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

    scrollDown = element => {

        return element.scrollTop(element[0].scrollHeight);
    };

    createFollowButton = (clasS, data_id) => {

        return $('<a class="fa '+ clasS +' text-right">').attr('data-id', data_id);
    };

    $(document).on('click','.open-message', e => {

        e.preventDefault();

        data.to     = $(e.target).data('id');
        data.name   = $(e.target).text();
        data.avatar = $(e.target).parent().find('.followers-avatar');

        $.post('/selmessages',{
            'from'  : data.get_id,
            'to'    : data.to
        }, res => {
            data.message.fadeIn();
            data.sendInput  = $('.send-message');
            data.xsUserAvatar = "<img src='"+ data.avatar.attr('src') +"' class='rounded-circle xs-avatar'>";
            data.toFriendProfile    = data.xsUserAvatar + "<a href='http://github.dev/user/"+ data.to +"' target='_blank' >" + data.name + "</a>";
            data.userName.html(data.toFriendProfile);
            data.sendInput.attr("data-id",data.to);
            data.messageBody.html("");
            res.item.map( item => {

                if(item.from == data.get_id){
                    data.color      = "info";
                    data.position   = "right";
                }else{
                    data.color      = "success";
                    data.position   = "left";
                }
                data.date   = item.date.date.toString();
                data.create = createMessage(item.message, data.date, data.color, data.position);
                data.messageBody.append(data.create);
                scrollDown(data.messageBody);
            });

            if(res.seen == 1){
                data.messageText    = data.messageBody.find('.message-text').last();
                if(data.messageText.hasClass('text-right')){
                    data.messageText.find('.cite').last().append(data.seenText);
                }
            }

        });
    });


    /**
     * Close message window
     */
    $(document).on('click','.close', () => {

        data.message.fadeOut();
    });


});


