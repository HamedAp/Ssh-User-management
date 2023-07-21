$(document).ready(function() {
    $("#switch-theme").click(function(){

        if( $( "html" ).hasClass( "html-light" )) {
            $( "html" ).removeClass( "html-light" );
            $( ".right-side-item" ).addClass( "right-side-item-light" );
            $( ".right-side-item-light" ).removeClass( "right-side-item" );

            $( "#switch-theme" ).text( "حالت روشن" );
        } else {
            $( "html" ).addClass( "html-light" );
            $( "#switch-theme" ).text( "حالت تاریک" );
        }

        if( $( ".ul-items" ).hasClass( "right-side-item" )) {
            $( ".ul-items" ).removeClass( "right-side-item" );
            $( ".ul-items" ).addClass( "right-side-item-light" );
            

        } else {
            $( ".ul-items" ).removeClass( "right-side-item-light" );
            $( ".ul-items" ).addClass( "right-side-item" );

        }
    });
    
    
    if ($("#ssh-enable").html() == 'غیرفعال') {
        $("#ssh-enable-box").css({"background-color":"#ec4245", "color":"white"})
    }else if ($("#ssh-enable").html() == 'منقضی شده') {
        $("#ssh-enable-box").css({"background-color":"#ff9900", "color":"black"})
    }

    
});