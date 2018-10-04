(function($){
    $(function(){

        $('.sidenav').sidenav();
        $('.fixed-action-btn').floatingActionButton();
        $( window ).scroll(function() {
            $('.fixed-action-btn').css("bottom", "-60px");
        });
    }); // end of document ready
})(jQuery); // end of jQuery name space