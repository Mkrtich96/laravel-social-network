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
        'seenText'      : $('<cite>').addClass('cite last').text(' Seen!'),
        'notifMenu'     : $('<ul>').addClass('dropdown-menu').attr('aria-labelledby', "navbarDropdownMenuLink"),
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



    arrangeResponse = (data, status = null, type = null) => {

        if(data.status === "fail"){
            console.error(data.message);
        }else if(data[0].status === "fail"){
            console.error("Invalid "+ type +" "+ status +" response.")
        }

    };

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
    
});