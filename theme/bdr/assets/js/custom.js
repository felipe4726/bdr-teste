function guidV2() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }

    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

function triggerMessages(messages) {
    $.each(messages, function (index, value) {
        idRandom = guidV2();
        $('.message-box').append(
            $('<div>').attr('id', idRandom).addClass('messages').html(value)
        );
        $('#' + idRandom).delay(4500).fadeOut("slow", function () {
            $("#" + idRandom).remove()
        });
    });
}

function triggerErrors(messages) {
    $.each(messages, function (index, value) {
        idRandom = guidV2();
        $('.message-box').append(
            $('<div>').attr('id', idRandom).addClass('errors').html(value)
        );
        $('#' + idRandom).delay(4500).fadeOut("slow", function () {
            $("#" + idRandom).remove()
        });
    });
}

$(document).ready(function () {
    // MENU LOGIN
    $(".minha-conta").click(function (event) {
        $(this).addClass("ativo");
        $('.sub-menu-conta').show();
        $('html').one('mousedown', function () {
            $(".minha-conta").removeClass("ativo");
            $('.sub-menu-conta').hide();
        });
    });
    $(".sub-menu-conta").bind('mousedown', function (evt) {
        evt.stopPropagation();
    });


    // MENU PRINCIPAL
    $(".menu-item").hover(
        function () {
            $(this).next('.sub-menu').show();
        },
        function () {
            $(this).next('.sub-menu').hide();
        }
    );
    $(".sub-menu").hover(
        function () {
            $(this).show();
            $(this).prev('a').addClass("ativo");
        },
        function () {
            $(this).hide();
            $(this).prev('a').removeClass("ativo");
        }
    );

    $(".btn-fechar").click(function () {
        $('.sub-menu-conta').hide();
    });

});
