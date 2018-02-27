{extends file="layout.tpl"}
{block name='head:title'}opinion{/block}
{block name='body:id'}opinion{/block}
{block name="stylesheets"}
    {headlink rel="stylesheet" href="/{baseadmin}/min/?f=plugins/opinion/css/admin.css" media="screen"}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des témoignages">opinion</a></h1>
{/block}
{block name="article:content"}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-xs-12 col-md-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{#last_opinion#|ucfirst}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
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
                </div>
            </section>
        </div>
        {include file="modal/edit.tpl"}
        {include file="modal/validate.tpl"}
        {include file="modal/delete.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=plugins/opinion/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
{/block}