{script src="js/addons/ct_faq/ct-faq.js"}

<div class="ct-side-wrap">
    {foreach from=$items name="category" item=category}
        <h3>{$category.name}</h3>
        <dl class="ct-faq-dynamic">
            {assign var="questions" value=$category.category_id|fn_get_faq_all_questions:$category.lang_code}
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
        </dl>
    {/foreach}
</div>