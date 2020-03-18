jQuery(document).ready(function ($) {

    /* 
    *   When you click on the #clickcopycode link, the script copies the input text(#text-shortcodepage) in the clipboard.
    */

    jQuery("#clickcopycode").click(function () {

        $('#text-shortcodepage').focus();
        $('#text-shortcodepage').select();
        document.execCommand('copy');

    });

    /* 
    *   When you click on the #clickcopy link, the script copies the input text(#text-shortcode) in the clipboard.
    */

    jQuery("#clickcopy").click(function () {

        $('#text-shortcode').focus();
        $('#text-shortcode').select();
        document.execCommand('copy');

    });

    /* 
    *   When you click on the #btnCreatePage button, the script creates a shortcode text and inserts it in the input text(#text-shortcodepage).
    */

    jQuery("#btnCreatePage").click(function () {
        var shortcode = "[CoursesShortcode";
        if ($("#imageCheck").prop("checked")) {
            shortcode += " image=true";
        }
        shortcode += "]";
        $("#text-shortcodepage").prop('disabled', false);
        $("#text-shortcodepage").val(shortcode);
    });

    /* 
    *   When you click on the #btnCreate button, the script creates a shortcode text and inserts it in the input text(#text-shortcode).
    */

    jQuery("#btnCreate").click(function () {
        var shortcode = "[indexShortcode";
        if ($("#selector option:selected").text() != 'Select courses') {
            shortcode += " category='" + $.trim($("#selector option:selected").text()) + "' ";
            if ($("#subtitleCheck").prop("checked")) {
                shortcode += " h2=true ";
            }

            if ($("#linkCheck").prop("checked")) {
                shortcode += " link=true ";
            }
            shortcode += "]";
            $("#text-shortcode").prop('disabled', false);
            $("#text-shortcode").val(shortcode);
        } else {
            $("#text-shortcode").prop('disabled', true);
            $("#text-shortcode").val('text');
        }
    });

});
