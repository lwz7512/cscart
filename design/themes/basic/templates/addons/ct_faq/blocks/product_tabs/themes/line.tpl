<div class="ct-faq-static line">

        <dl>
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