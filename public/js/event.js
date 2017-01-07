function sortList(obj){
    window.location.href = location.protocol + '//' + location.host + location.pathname+"?sort=" + obj.val();
}

$(function() {
    $('.checkbox_record').click(function() {

        var id = $(this).attr('id');
        var status = $(this).is(':checked') == true ? 1 : 0;
        var objThis = $(this);
        $.ajax({
            type: "POST",
            url: "ajax/backend/event/list",
            data: { call: "change_status", id: id, status: status}
        }).done(function( msg ) {
            if (msg) {
                var objMsg = $.parseJSON(msg);
                if (objMsg.message != '') {
                    $('#info_'+id).html(objMsg.message);
                }
                //objThis.parents('.dropdown').first().find('.dropdown-toggle').html(objThis.text() + '<span class="caret"></span>');
            }
        });
    });
});
