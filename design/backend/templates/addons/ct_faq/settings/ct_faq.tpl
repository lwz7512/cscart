<div class="form-field">
	<label for="product_theme" style="width:193px;">Theme for detailed product page:</label>
	<input name="ct_settings[product_theme]" type="radio" value="blue" {if $faq_theme_data.product_theme == "blue"}checked="checked"{/if} class="show-box-blue"> Blue theme
	<input name="ct_settings[product_theme]" type="radio" value="grey" {if $faq_theme_data.product_theme == "grey"}checked="checked"{/if} class="show-box-grey"> Grey theme
	<input name="ct_settings[product_theme]" type="radio" value="side" {if $faq_theme_data.product_theme == "side"}checked="checked"{/if} class="show-box-side"> Side theme
	<input name="ct_settings[product_theme]" type="radio" value="static" {if $faq_theme_data.product_theme == "static"}checked="checked"{/if} class="show-box-static"> Static theme
</div>
<div class="form-field">
	<label for="faq_theme" style="width:193px;">Theme for FAQ page:</label>
	<input name="ct_settings[faq_theme]" type="radio" value="blue" {if $faq_theme_data.faq_theme == "blue"}checked="checked"{/if} class="show-box-blue"> Blue theme
	<input name="ct_settings[faq_theme]" type="radio" value="grey" {if $faq_theme_data.faq_theme == "grey"}checked="checked"{/if} class="show-box-grey"> Grey theme
	<input name="ct_settings[faq_theme]" type="radio" value="side" {if $faq_theme_data.faq_theme == "side"}checked="checked"{/if} class="show-box-side"> Side theme
	<input name="ct_settings[faq_theme]" type="radio" value="static" {if $faq_theme_data.faq_theme == "static"}checked="checked"{/if} class="show-box-static"> Static theme
</div>
<div id="image_blue" style="display: none;">
	<img src="addons/ct_faq/images/blue.png">
</div>
<div id="image_grey" style="display: none;">
	<img src="addons/ct_faq/images/grey.png">
</div>
<div id="image_side" style="display: none;">
	<img src="addons/ct_faq/images/side.png">
</div>
<div id="image_static" style="display: none;">
	<img src="addons/ct_faq/images/static.png">
</div>
