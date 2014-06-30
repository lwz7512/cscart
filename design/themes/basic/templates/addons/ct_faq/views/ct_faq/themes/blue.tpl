{script src="js/addons/ct_faq/ct-faq.js"}
<script type="text/javascript">
    $(function url_open(){ldelim}
        this_hash = window.location.hash.replace('#','');
        console.log(this_hash);

        element = document.getElementById(this_hash);

        if (element != null) {ldelim}console.log(element); element.style.display="block";{rdelim}
        {rdelim})
</script>

{foreach from=$categories name="category" item=category}
    <h2>{$category.name}</h2>
    <dl class="ct-faq-dynamic faq-blue">
        {foreach from=$category.questions name=question item=question}
            <dt {if $question.visible == "Y"}class=" ct-active" {/if}>
                <span class="ct-faq-q"></span>
                <span class="ct-faq-arrow"></span>
            <div>
                {$question.question nofilter}
            </div>
            </dt>
            <dd name="#{$question.anchor nofilter}" onLoad="url_open();" id="{$question.anchor nofilter}" {if $question.visible == "Y"}class=" ct-active" {/if}>
                <div>
                    {$question.answer nofilter}
                </div>
            </dd>
        {/foreach}
    </dl>
{/foreach}