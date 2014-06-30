{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}

<script type="text/javascript">

$(document).on('click','.show-box-blue',function() {ldelim}
	 $('#image_blue').show();
	 $('#image_grey').hide();
	 $('#image_side').hide();
	 $('#image_static').hide();
{rdelim});

$(document).on('click','.show-box-grey',function() {ldelim}
	 $('#image_blue').hide();
	 $('#image_grey').show();
	 $('#image_side').hide();
	 $('#image_static').hide();
{rdelim});

$(document).on('click','.show-box-side',function() {ldelim}
	 $('#image_blue').hide();
	 $('#image_grey').hide();
	 $('#image_side').show();
	 $('#image_static').hide();
{rdelim});

$(document).on('click','.show-box-static',function() {ldelim}
	 $('#image_blue').hide();
	 $('#image_grey').hide();
	 $('#image_side').hide();
	 $('#image_static').show();
{rdelim});

</script>