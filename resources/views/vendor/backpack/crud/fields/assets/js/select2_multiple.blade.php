<!-- include select2 js-->
<script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>
<script>
	jQuery(document).ready(function($) {
		// trigger select2 for each untriggered select2_multiple box
		$('.select2').each(function (i, obj) {
            if (!$(obj).data("select2"))
            {
                $(obj).select2();
            }
        });
	});
</script>