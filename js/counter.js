var lastCount = 0;

counter = function() {
    var value = $('#entry_body').val() || $('#entry_body' ).text();

    if (value != undefined && value.length == 0) {
        $('#wordCount').html(0);
        $('#totalChars').html(0);
        $('#charCount').html(0);
        $('#charCountNoSpace').html(0);
        $('#this_day_ok' ).css('display', 'none');
        check_count(0);
        return;
    }

    var regex = /\s+/gi;
    var wordCount = value.trim().replace(regex, ' ').split(' ').length;
    var totalChars = value.length;
    var charCount = value.trim().length;
    var charCountNoSpace = value.replace(regex, '').length;


    $('#wordCount').html(wordCount);
    $('#totalChars').html(totalChars);
    $('#charCount').html(charCount);
    $('#charCountNoSpace').html(charCountNoSpace);

    check_count(wordCount);
};


function check_count(count){
    var $icon = $( '#this_day_ok' );
    if(count >= 450){
        $icon.removeClass( 'fa-square-o' );
        $icon.addClass( 'fa-check-square-o' );
    }else{
        $icon.removeClass( 'fa-check-square-o' );
        $icon.addClass( 'fa-square-o' );
    }

    lastCount = count;
}

$(document).ready(function() {
    counter(); // update at least once
    if( $( 'textarea' ).size() == 0) return; // no textarea

    $('#entry_body').change(counter);
    $('#entry_body').keydown(counter);
    $('#entry_body').keypress(counter);
    $('#entry_body').keyup(counter);
    $('#entry_body').blur(counter);
    $('#entry_body').focus(counter);
    // auto adjust the height of
    //$('#textarea_container').on( 'keyup', 'textarea', function (){
    //    if(this.scrollHeight <= 300) return;
    //    $(this).height( 0 );
    //    $(this).height( this.scrollHeight );
    //});
    //$('#textarea_container').find( 'textarea' ).keyup();
    autosize($('textarea'));

});