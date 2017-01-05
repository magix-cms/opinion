{extends file="catalog/{$smarty.get.section}/edit.tpl"}
{block name="styleSheet" append}
    {include file="css.tpl"}
{/block}
{block name="forms"}
    <header>
        <p class="lead pull-right">
            {#global_rating#|ucfirst}&nbsp;:
            {for $i=1 to {$avgRating.avgRating|round}}
                <span class="fa fa-star text-yellow"></span>
            {/for}
            {for $i=1 to (5 - {$avgRating.avgRating|round})}
                <span class="fa fa-star text-muted"></span>
            {/for}
        </p>
        <h2>{#list_opinion#|ucfirst}</h2>
    </header>
    <table id="pending_list" class="table table-bordered table-condensed table-hover">
        <thead>
        <tr>
            <th>{#opinion_name#|ucfirst}</th>
            <th>{#opinion_email#|ucfirst}</th>
            <th>{#opinion_content#|ucfirst}</th>
            <th>{#opinion_note#|ucfirst}</th>
            <th>{#opinion_status#|ucfirst}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $validated as $op}
            {include file="loop/op.tpl"}
        {/foreach}
        </tbody>
    </table>
    {$pagination}
    {include file="modal/edit.tpl"}
    {include file="modal/validate.tpl"}
    {include file="modal/delete.tpl"}
{/block}
{block name="javascript"}
    {include file="js.tpl"}
{/block}