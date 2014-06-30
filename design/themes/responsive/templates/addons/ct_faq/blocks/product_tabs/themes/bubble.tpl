<div class="ct-faq-static buble">

        <dl>
            {foreach from=$questions name=question item=question}
                <dt>
                    <div>
                        <span class="ct-q">{__("ct_faq_question_front")}</span>
                        {$question.question nofilter}
                    </div>
                </dt>
                <dd>
                    <div>
                        <span class="ct-a">{__("ct_faq_answer_front")}</span>
                        {$question.answer nofilter}
                    </div>
                </dd>
            {/foreach}
            {foreach from=$global_questions name=question item=question}
                <dt>
                <div>
                    <span class="ct-q">{__("ct_faq_question_front")}</span>
                    {$question.question nofilter}
                </div>
                </dt>
                <dd>
                    <div>
                        <span class="ct-a">{__("ct_faq_answer_front")}</span>
                        {$question.answer nofilter}
                    </div>
                </dd>
            {/foreach}
        </dl>

</div>