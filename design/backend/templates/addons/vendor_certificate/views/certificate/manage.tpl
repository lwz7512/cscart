{capture name="mainbox"}
    hello!
{/capture}


{capture name="adv_buttons"}
    {include file="common/tools.tpl"
    tool_href="certificate.add"
    prefix="top"
    hide_tools=true
    title=__("add_certificate")
    icon="icon-plus"
    }
{/capture}

{include file="common/mainbox.tpl"
title=__("manage_certificate")
adv_buttons=$smarty.capture.adv_buttons
content=$smarty.capture.mainbox
select_languages=true
}
