{script src="js/addons/ct_faq/ct-faq.js"}

{foreach from=$items name="category" item=category}
    <h2>{$category.name}</h2>
    <dl class="ct-faq-dynamic faq-blue">
        {assign var="questions" value=$category.category_id|fn_get_faq_all_questions:$category.lang_code}
        {foreach from=$questions name=question item=question}
            <dt>
                <span class="ct-faq-q"></span>
                <span class="ct-faq-arrow"></span>
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