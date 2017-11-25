(() => {

    data = {
        "cards"         : $('.cards'),
        "search_win"    : $('.modal-search-users').find('.modal-body'),
        "cardTitle"     : $('<h4 class="card-title">'),
        "button"        : $('<button>'),
        "lists"         : $('.list-group-item'),
        "dropdowns"     : $('.dropdowns'),
        "menu"          : $('.dropdown-menu'),
        "modal_friends" : $('.modal-search-users'),
        "input"         : $('<input>'),
        "search_friends": $(".search-friends"),
        "followers"     : $(".list-group"),
        "message"       : $('.message'),
        "userName"      : $('.user-name'),
        'get_id'        : $('.get-id').data('id'),
        'images'        : $('.gallery-img'),
        'row'           : $('.images'),
        'cite'          : $("<cite>"),
        'messageBody'   : $('.anyClass'),
        'citeElements'  : $('.cite'),
        'seenText'      : $('<cite>').addClass('cite last').text(' Seen!'),
        'notif'         : $('.notifications'),
        'notifMenu'     : $('<ul>').addClass('dropdown-menu').attr('aria-labelledby', "navbarDropdownMenuLink"),
        'form_post'     : $('.form-post'),
        'profile_photo' : $('.card-img-top').attr('src'),
        'profile_name'  : $('.navbar-brand').text(),
    };

    arrangeResponse = (data, status = null, type = null) => {
        if(data.status === "fail"){
            console.error(data.message);
        }else{
            console.error(data[0]);
        }
    };

    createMessage = (message, user = false, color, sender)  => {


        data.li = $('<li>').addClass(`list-group-item list-group-item-${color} text-${sender} message-text`)
                            .attr('data-id', message.id)
                            .append(message.message + "<br><cite class='cite' title='"+message.created_at+"'>" +message.created_at+ "</cite>");

        if(user === false){

            return data.li;
        }else if(user.avatar === null){

            data.avatar = 'http://github.dev/images/avatars/male.gif';
        }else{

            data.avatar = `http://github.dev/images/${user.id}/${user.avatar}`;
        }

        data.img = $('<img>').addClass('rounded-circle conversations-avatar float-' + sender)
            .attr('src', data.avatar);

        data.li.prepend("<h6 class='mb-1'>"+ user.name +"</h6>").prepend(data.img);

        return data.li;
    };

    scrollDown = element => element.scrollTop(element[0].scrollHeight);


    createFollowButton = (clasS, data_id) => $('<a class="fa '+ clasS +' text-right">').attr('data-id', data_id);


})();


