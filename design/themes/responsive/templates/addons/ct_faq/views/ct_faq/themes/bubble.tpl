<script type="text/javascript">
    $(function url_open(){ldelim}
        this_hash = window.location.hash.replace('#','');
        console.log(this_hash);

        element = document.getElementById(this_hash);

        if (element != null) {ldelim}console.log(element); element.style.display="block";{rdelim}
        {rdelim})
</script>
<div class="ct-faq-static buble">
    {foreach from=$categories name="category" item=category}
        <h3>{$category.name}</h3>
        <dl>
            {foreach from=$category.questions name=question item=question}
                <dt {if $question.visible == "Y"}class=" ct-active" {/if}>
                    <div>
                        <span class="ct-q">{__("ct_faq_question_front")}</span>
                        {$question.question nofilter}
                    </div>
                </dt>
                <dd name="#{$question.anchor nofilter}" onLoad="url_open();" id="{$question.anchor nofilter}" {if $question.visible == "Y"}class=" ct-active" {/if}>
                    <div>
                        <span class="ct-a">{__("ct_faq_answer_front")}</span>
                        {$question.answer nofilter}
                    </div>
                </dd>
            {/foreach}
        </dl>
    {/foreach}
</div>