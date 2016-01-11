/* TK: Ready, Set, Go. */
$(document).ready(function() {

    /**
	 * Toggle containers.
	 */
	$(document).on("click", ".toggle", function(event) {
	    event.preventDefault();
		var target = $(this).attr("data-target");
		var me = $(this);
        $('#'+target).slideToggle({
            complete: function() {
                me.toggleClass('open')
            }
        });
	});
	
	$('form input[type=submit]').click(function () {
	    $('input[type=submit]', $(this).parents('form')).removeAttr('clicked');
	    $(this).attr('clicked', true);
	});
	
	$('form.panel').submit( function( event ) {
        var btn = $('input[type=submit][clicked=true]');
        btn.addClass('processing');
        btn.attr('disabled', 'disabled');
        $('.notification').hide();
        //alert( 'Val ' + btn.val() );
        return true;
	});
	
	/**
     * Anchors with .ask class will call their URL only if confirmation is given.
     */
    $(document).on("click", 'a.ask', function(event) {
        if ( ! confirm($(this).attr('data-question'))) {
            return false;
        }
        return true;
    });
    
	/**
     * Elements with class .anchor-substitue will act as normal a anchor elements.
     */
    $(document).on("click", '.anchor-substitute', function(event) {
        window.location.href = $(this).attr('data-href');
        return false;
    });

    /**
     * Elements with .silent class will call an URL and load the content
     * into a container using ajax.
     */
    $(document).on("click", 'button.silent', function(event) {
        event.preventDefault();
        var container = $(this).attr("data-container");
        var href = $(this).attr("data-href");
        $.get(href, function(data) {
            $("#"+container).empty();
            $("#"+container).append(data);
        }, "html");
    });
});
