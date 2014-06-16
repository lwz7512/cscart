<div id="content_trip_build" class="hidden">
    {include file="common/subheader.tpl" title="Supplementary Info" target="#trip_build_products_hook"}
    <div id="trip_build_products_hook" class="in collapse">
        <!--product address-->
        <div class="control-group {$no_hide_input_if_shared_product}">
            <label for="product_description_address" class="control-label">Address:</label>
            <div class="controls">
                <input id="product_description_address" name="product_data[address]" form="form" type="text" class="input-large" value="{$product_data.trip.address}"/>
            </div>
        </div>
        <!--other properties-->

        <!--product characteristics-->
        <div class="control-group">
            <label for="elm_product_characteristics" class="control-label">Characteristics:</label>
            <div class="controls">
                <textarea id="elm_product_characteristics" name="product_data[characteristics]" cols="55" rows="2" class="cm-wysiwyg input-large">{$product_data.trip.characteristics}</textarea>
            </div>
        </div>
        <!--product attentions-->
        <div class="control-group">
            <label for="elm_product_attentions" class="control-label">Notes:</label>
            <div class="controls">
                <textarea id="elm_product_attentions" name="product_data[notes]" cols="55" rows="2" class="cm-wysiwyg input-large">{$product_data.trip.notes}</textarea>
            </div>
        </div>
        <!--product must known-->
        <div class="control-group">
            <label for="elm_product_must_known" class="control-label">Must Known:</label>
            <div class="controls">
                <textarea id="elm_product_must_known" name="product_data[must_known]" cols="55" rows="2" class="cm-wysiwyg input-large">{$product_data.trip.must_known}</textarea>
            </div>
        </div>

        <!--more ...-->

    </div>
</div><!--end of content_trip_build-->
