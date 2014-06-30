<div class="ct-faq-static buble">
    {foreach from=$items name="category" item=category}
        <h3>{$category.name}</h3>
        <dl>
            {assign var="questions" value=$category.category_id|fn_get_faq_all_questions:$category.lang_code}
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
        </dl>
    {/foreach}
</div>