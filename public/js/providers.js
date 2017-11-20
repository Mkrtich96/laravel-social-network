$(() => {

    let copyToClipboard = element => {
        $("body").append(data.input);
        data.input.val(element).select();
        document.execCommand("copy");
        data.input.remove();
    };

    $('.login-git').on("click", e => {
        e.preventDefault();
        $("#app").hide();
        $(".loader").addClass('active');
        location.assign('/auth/github');
        window.onload = () => $(".loader").fadeOut();
    });

    $('.clone-copy').on("click", () => {
        let copy = $(e.target).parent().find(".copy-val").val();
        copyToClipboard(copy);
    }).popover({
        trigger:'focus',
        content:'Copied!',
        placement:'bottom'
    });
});