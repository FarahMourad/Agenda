$(document).ready(function() {
    $(".disable").on('click', function () {
        // * = select All, find Div, Not (#video) and edit css opacity
        $("*").find('div').not("#newNotte").css('opacity', '0.1');

    });
});
