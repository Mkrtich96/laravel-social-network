$(() => {


    let searchRes = null;

    /**
     * Search autocompleate
     */
    data.search.autocomplete({
        source  : '/search',
        response: (event,ui) => {
            searchRes = ui.content;
        }
    });

    $(document).on('click',".accept-follow", e => {

        e.preventDefault();
        data.this           =   $(e.target);
        data.parent         =   data.this.parent();
        data.follower_id    =   data.this.data('id');

        $.ajax({
            method : "POST",
            url    : "/accept",
            data   : {
                "to": data.follower_id
            },
            success : response => {

                if(response.status === "success") {
                    data.unfllwBtn = $("<a>").addClass('btn btn-secondary float-right unfollow')
                                                .text("Unfollow");

                    data.crtFollow = $("<li>").addClass('list-group-item');
                    data.badge = data.dropdowns.find(".badge-danger");
                    data.countNot = parseInt(data.dropdowns.find(".badge-danger").text());
                    if (data.countNot > 1) {
                        data.badge.text(data.countNot - 1);
                    } else {
                        data.badge.remove();
                    }
                    data.parent.text("Request accepted!");

                    data.unfllwBtn.attr("data-id", response.id);

                    data.avatar = $('<img>').addClass('rounded-circle followers-avatar')
                                            .attr('src', response.avatar);

                    data.openMessage = $('<a>').addClass('open-message text-primary')
                                                .attr('data-id', data.follower_id)
                                                .text(response.name);
                    data.crtFollow.append(data.avatar)
                                    .append(data.openMessage)
                                    .append(data.unfllwBtn);
                    data.followers.prepend(data.crtFollow);
                    setTimeout(() => {
                        data.parent.remove();
                        data.menu.remove();
                    }, 3000);
                }else{
                    console.error("Invalid accept response! Connection error.")
                }
            },
            statusCode: {
                404: res => {
                    arrangeResponse(res.responseJSON);
                },
                422: res =>  {

                    arrangeResponse(res.responseJSON, 422, "accept");
                }
            }
        })
    });



    $(document).on('click',".cancel-follow", e => {

        e.preventDefault();
        data.this   = $(e.target);
        data.parent = data.this.parent();
        data.follower_id = data.this.data('id');
        data.accidentally = data.parent.hasClass('header-request') ? 'to' : 'notifiable_id';

        data.ajaxData = {};

        data.ajaxData[data.accidentally] = data.follower_id;


        $.ajax({
            method  :   "POST",
            url     :   "/cancel",
            data    :   data.ajaxData,
            success :  response => {
                if(response.status === "success"){
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
                        setTimeout(() => {
                            data.parent.remove();
                            data.menu.remove();
                        },3000)
                    }else{
                        data.this.removeClass()
                            .addClass('btn btn-outline-primary follow')
                            .attr("data-id",response.id)
                            .text("Follow");
                    }
                }else{
                    console.error("Invalid cancel response! Connection error.")
                }
            },
            statusCode  :   {
                404 : res => {

                    arrangeResponse(res.responseJSON);
                },
                422 : res => {

                    arrangeResponse(res.responseJSON, 422, "cancel");
                }
            }
        });
    });

    $(document).on('click',".unfollow", e => {

        e.preventDefault();
        data.this = $(e.target);
        data.parent = data.this.parent();
        data.follower_id = data.this.data('id');

        $.ajax({
            method : "POST",
            url    : "/unfollow",
            data   : {
                "follower_id" : data.follower_id,
            },
            success : response => {
                if(response.status === "success"){
                    if(data.parent.hasClass('list-group-item')){
                        data.parent.remove();
                    }else{
                        data.this.removeClass('btn-primary unfollow').addClass('btn-outline-primary follow')
                            .attr("data-id",response.id)
                            .text("Follow");
                        $('.list-group-item').find("[data-id="+data.follower_id+"]").parent().remove();
                    }
                }else{
                    console.error("Invalid unfollow response! Connection error.")
                }
            },
            statusCode: {
                404:    res  => {
                    arrangeResponse(res.responseJSON);
                },
                422:    res  =>  {
                    arrangeResponse(res.responseJSON, 422, "unfollow");
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

                if(response.status === "success") {
                    data.this.removeClass('btn-outline-primary follow')
                        .attr("data-id", response.id)
                        .addClass('btn-secondary cancel')
                        .text("Cancel Request");
                }else{
                    console.log("Invalid follow response. Connection error.")
                }
            },
            statusCode: {
                404:    res  => {
                    arrangeResponse(res.responseJSON);
                },
                422:    res  =>  {
                    arrangeResponse(res.responseJSON, 422, "follow");
                }
            }
        })

    });

    $(document).on("click",".search-btn", e => {

        e.preventDefault();

        if(!$.isEmptyObject(searchRes)){

            $('.modal-search-users').modal('show');
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
                                            }).text(item.value);
                data.cardTitle.append(data.cardLink);

                switch(item.follow) {
                    case 1: data.button.addClass('btn btn-secondary unfollow')
                                        .attr("data-id", item.id)
                                        .text("Unfollow");
                                        break;
                    case 2: data.button.removeClass('btn-outline-primary follow')
                                        .attr("data-id", item.id)
                                        .addClass('btn btn-secondary cancel-follow')
                                        .text("Cancel Request");
                                        break;
                    default: data.button.addClass('btn btn-outline-primary follow')
                                            .attr("data-id", item.id)
                                            .text("Follow");

                }

                data.textDiv.append(data.button);
                data.cards3.append(data.avatar);
                data.cards3.append(data.cardTitle);
                data.cards3.append(data.textDiv);
                data.cards2.append(data.cards3);
                data.cards1.append(data.cards2);
                data.search_win.append(data.cards1);
            });
        }else{
            console.error('Error with searching. Please revise jquery ui autocomplete function!')
        }
    });




});