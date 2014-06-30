{script src="js/addons/ct_faq/ct-faq.js"}


    <dl class="ct-faq-dynamic faq-blue faq-grey">
        {foreach from=$questions name=question item=question}
            <dt>
                <span class="ct-faq-q"></span>
                <span class="ct-faq-arrow"></span>
            <div style="color: #000;">
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
                <span class="ct-faq-q"></span>
                <span class="ct-faq-arrow"></span>
            <div style="color: #000;">
                {$question.question nofilter}
            </div>
            </dt>
            <dd>
                <div >
                    {$question.answer nofilter}
                </div>
            </dd>
        {/foreach}
    </dl>






