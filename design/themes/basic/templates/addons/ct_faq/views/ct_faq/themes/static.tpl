<script type="text/javascript">
    $(function url_open(){ldelim}
        this_hash = window.location.hash.replace('#','');
        console.log(this_hash);

        element = document.getElementById(this_hash);

        if (element != null) {ldelim}console.log(element); element.style.display="block";{rdelim}
        {rdelim})
</script>
<div class="ct-faq-static">
    {foreach from=$categories name="category" item=category}
        <h3>{$category.name}</h3>
        <dl>
            {foreach from=$category.questions name=question item=question}
                <dt {if $question.visible == "Y"}class=" ct-active" {/if}>
                    {$question.question nofilter}
                </dt>
                <dd name="#{$question.anchor nofilter}" onLoad="url_open();" id="{$question.anchor nofilter}" {if $question.visible == "Y"}class=" ct-active" {/if}>
                    {$question.answer nofilter}
                </dd>
            {/foreach}
        </dl>
    {/foreach}
</div>