{script src="js/addons/ct_faq/ct-faq.js"}

<div class="ct-side-wrap">
        <dl class="ct-faq-dynamic">
            {foreach from=$questions name=question item=question}
                <dt>
                    <div>
                        {$question.question nofilter}
                    </div>
                </dt>
                <dd>
                    <div>
                        {$question.answer nofilter}
                    </div>
                </dd>
            {/foreach}
            {foreach from=$global_questions name=question item=question}
                <dt>
                <div>
                    {$question.question nofilter}
                </div>
                </dt>
                <dd>
                    <div>
                        {$question.answer nofilter}
                    </div>
                </dd>
            {/foreach}
        </dl>
</div>