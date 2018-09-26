jQuery(function($) {

    $(document).on('click', '.dpmt-toggle', function(){

        $('div[data-toggle="' + $(this).data('toggle') + '"]').slideToggle();

    });

});