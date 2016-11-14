{autoload_i18n}
{switch $message}
    {********* Success *********}
{case 'add' break}
    {** Add **}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_add#}
{/capture}
    {********* Warning *********}
    {** Empty **}
{case 'empty' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_empty#}
{/capture}
{/switch}
<p class="{if $message neq 'error_login' AND $message neq 'error_hash'}col-sm-12{/if} alert alert-{$smarty.capture.alert_type} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="fa fa-{$smarty.capture.icon} fa-lg"></span> {$smarty.capture.alert_message}
</p>