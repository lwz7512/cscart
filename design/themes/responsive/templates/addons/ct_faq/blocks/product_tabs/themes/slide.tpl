{script src="js/addons/ct_faq/ct-faq.js"}

<div class="ct-slide-wrap">
        <dl class="ct-faq-dynamic">
            {foreach from=$questions name=question item=question}
                <dt>
                    <span class="ct-status"></span>
                    {$question.question nofilter}
                </dt>
                <dd>
                    {$question.answer nofilter}
                </dd>
            {/foreach}
            {foreach from=$global_questions name=question item=question}
                <dt>
                    <span class="ct-status"></span>
                    {$question.question nofilter}
                </dt>
                <dd>
                    {$question.answer nofilter}
                </dd>
            {/foreach}
        </dl>
</div>