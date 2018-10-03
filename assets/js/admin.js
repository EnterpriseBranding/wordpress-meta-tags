jQuery(function($) {

    // show/hide extra info
    $(document).on('click', '.dpmt-toggle', function(){

        $('div[data-toggle="' + $(this).data('toggle') + '"]').slideToggle();

    });



    // set all fields to autopilot
    $(document).on('click', '.dpmt-set-all-auto', function(e){

        e.preventDefault();

        $('.dpmt-editor form input[type=text], .dpmt-editor form select').val('auto');

    });



    // clear all fields
    $(document).on('click', '.dpmt-clear-all', function(e){

        e.preventDefault();

        $('.dpmt-editor form input[type=text], .dpmt-editor form select, .dpmt-editor form textarea').val('');

    });

});