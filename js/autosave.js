var last_text = "";

function save(){
    var day = $( '#day' ).val();
    var text = $( '#entry_body' ).val().trim();

    if( text == last_text ){
        $.ajax( {url: "/keepalive.php"} )
            .done( function( ans ){
                console.log( ans );
            } )
            .fail(reload);

    }else{
        console.log( get_cur_time() + ' saving.' );

        $.ajax( {
            method: "POST",
            url   : "/autosave.php",
            data  : {day: day, word: text}
        } ).done( function( ans ){
            console.log( ans );
            if( ans.match( '.* 1' ) ){
                $( '#saved' ).css( 'display', 'block' );
                $( '#saved_words' ).text( $('#wordCount' ).text() );
                $( '#saved_time' ).text( get_cur_time() );
                last_text = text;
            }
        } ).fail(reload);
    }
}


function reload(){
    location.reload();
}

function get_cur_time(){
    var curdate = new Date();
    return curdate.getHours() + ':' + curdate.getMinutes();
}

$( document ).ready( function(){
    if( $( 'textarea' ).size() == 0) return; // no textarea

    last_text = $( '#entry_body' ).val().trim();
    setInterval( function(){
        save();
    }, 60000 );
} );

function get_textarea_value(){
    var text = $( '#entry_body' ).val().trim();

}