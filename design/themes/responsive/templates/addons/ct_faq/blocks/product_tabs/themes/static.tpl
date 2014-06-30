<div class="ct-faq-static">
        <dl>
            {foreach from=$questions name=question item=question}
                <dt>
                    {$question.question nofilter}
                </dt>
                <dd>
                    {$question.answer nofilter}
                </dd>
            {/foreach}
            {foreach from=$global_questions name=question item=question}
                <dt>
                    {$question.question nofilter}
                </dt>
                <dd>
                    {$question.answer nofilter}
                </dd>
            {/foreach}
        </dl>
</div>