{include file="opinion/modal/opinion.tpl"}
{script src="/min/?f=plugins/opinion/js/public.js" concat=$concat type="javascript"}
<script>
    var iso = '{getlang}';
    $(function() {
        if (typeof Mc_plugins_opinion == "undefined") {
            console.log("Mc_plugins_opinion is not defined");
        } else {
            Mc_plugins_opinion.run(iso);
        }

        /** Modal **/
        $("#rating-star").rating();
        $('#rating-star').on('rating.change', function(event, value, caption) {
            $('#rating').prop('selectedIndex',value);
        });
        $('#rating').change(function(){
            $('#rating-star').rating('update', this.value);
        });
    });
</script>