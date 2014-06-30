<div class="ct-faq-static">
    {foreach from=$items name="category" item=category}
        <h3>{$category.name}</h3>
        <dl>
            {assign var="questions" value=$category.category_id|fn_get_faq_all_questions:$category.lang_code}
            {foreach from=$questions name=question item=question}
                <dt>
                    {$question.question nofilter}
                </dt>
                <dd>
                    {$question.answer nofilter}
                </dd>
            {/foreach}
        </dl>
    {/foreach}
</div>