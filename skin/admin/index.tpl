{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="styleSheet" append}
    {include file="css.tpl"}
{/block}
{block name="article:content"}
    {include file="nav.tpl"}
    <!-- Notifications Messages -->
    <div class="mc-message clearfix"></div>
    <h1>{#last_opinion#|ucfirst}</h1>
    <table id="pending_list" class="table table-bordered table-condensed table-hover">
        <thead>
            <tr>
                <th>{#opinion_product#|ucfirst}</th>
                <th>{#opinion_name#|ucfirst}</th>
                <th>{#opinion_email#|ucfirst}</th>
                <th>{#opinion_content#|ucfirst}</th>
                <th>{#opinion_note#|ucfirst}</th>
                <th>{#opinion_status#|ucfirst}</th>
                <th class="text-center">
                    <span class="fa fa-edit"></span>
                </th>
                <th class="text-center">
                    <span class="fa fa-trash-o"></span>
                </th>
            </tr>
        </thead>
        <tbody>
            {foreach $pending as $op}
                {include file="loop/op.tpl"}
            {/foreach}
        </tbody>
    </table>
    {$pagination}
    {include file="modal/edit.tpl"}
    {include file="modal/validate.tpl"}
    {include file="modal/delete.tpl"}
{/block}
{block name='javascript'}
    {include file="js.tpl"}
{/block}