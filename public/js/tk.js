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

});
