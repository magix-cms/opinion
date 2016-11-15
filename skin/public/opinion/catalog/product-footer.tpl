{include file="opinion/modal/opinion.tpl"}
{script src="/min/?f=skin/{template}/js/vendor/star-rating.min.js,plugins/opinion/js/public.min.js" concat=$concat type="javascript"}
<script>
    var iso = '{getlang}';
    $(function() {
        if (typeof Mc_plugins_opinion == "undefined") {
            console.log("Mc_plugins_opinion is not defined");
        } else {
            Mc_plugins_opinion.run(iso);
        }
    });
</script>