{if !empty($smarty.session.cart.agenda)}
    <div class="ty-product-notification__item clearfix">
        {assign var="from_time" value=$smarty.session.cart.agenda.from_time}
        {assign var="to_time" value=$smarty.session.cart.agenda.to_time}
        <div class="ty-product-notification__content clearfix">
            <div class="ty-product-notification__price">
                <span class="ty-strong">Agenda:&nbsp;</span><span class="none">{$from_time}-{$to_time}</span>
            </div>
        </div>
    </div>
{/if}
