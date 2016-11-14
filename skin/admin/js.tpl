{if !$smarty.get.plugin}{script src="/{baseadmin}/min/?f=plugins/{$pluginName}/js/admin.js" concat={$concat} type="javascript"}{/if}
<script type="text/javascript">
    $.nicenotify.notifier = {
        box:"",
        elemclass : '.mc-message'
    };
    $(function(){
        $('[data-toggle="popover"]').popover();

        {if !$smarty.get.plugin}
        if (typeof MC_plugins_opinion == "undefined"){
            console.log("MC_plugins_opinion is not defined");
        }else{
            MC_plugins_opinion.run(baseadmin,getlang);
        }
        {/if}
    });
</script>