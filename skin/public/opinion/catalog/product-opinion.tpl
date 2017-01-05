<div id="opinions">
    {widget_opinion_data type="catalog" idproduct=$product.idcatalog}
    <h2 class="h3" id="opinion-section">{#opinions#|ucfirst}</h2>
    <div class="clearfix mc-message-opinion"></div>
    {if !empty($opinions)}
        <div class="opinion-list">
            {foreach $opinions as $op}
                {include file="opinion/loop/op.tpl"}
            {/foreach}
        </div>
    {/if}
    <p class="text-center add-comment">
        <a href="#" class="btn btn-box btn-invert btn-fr-theme" title="{#add_opinion#|ucfirst}" data-toggle="modal" data-target="#modal-opinion">
            {#add_opinion#|ucfirst}
        </a>
    </p>
</div>