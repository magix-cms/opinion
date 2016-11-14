{extends file="catalog/{$smarty.get.section}/edit.tpl"}
{block name="forms"}
    <div class="alert alert-info">
        <p><span class="fa fa-info"></span> {#plugin_not_installed#|ucfirst}</p>
    </div>
{/block}