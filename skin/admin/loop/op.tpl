<tr id="op_{$op.idopinion}">
    {if !$smarty.get.plugin}
        <td>
            <a href="{$op.url}" class="btn btn-link targetblank">
                {$op.titlecatalog}
            </a>
        </td>
    {/if}
    <td class="td-lg">{$op.pseudo_opinion}</td>
    <td class="td-lg">{$op.email_opinion}</td>
    <td class="op_content text-center">
        {if $op.msg_opinion}
            <a class="btn btn-link" role="button" data-toggle="popover" data-placement="bottom" data-trigger="click" title="{#msg_opinion#|ucfirst}" data-content="{$op.msg_opinion}">
                <span class="fa fa-eye"></span>
            </a>
        {else}
            <span class="fa fa-minus"></span>
        {/if}
    </td>
    <td class="td-lg text-center">{$op.rating_opinion}&nbsp;/&nbsp;5</td>
    {if $op.status_opinion}
        <td class="td-lg text-success">
            <span class="fa fa-check"></span> Validé
        </td>
    {else}
        <td>
            <a href="#" class="btn text-warning toggleModal" data-id="{$op.idopinion}" data-toggle="modal" data-target="#modal-validate">
                <span class="fa fa-clock-o"></span> En attente
            </a>
        </td>
    {/if}
    {if !$smarty.get.plugin}
        <td class="text-center">
            <a href="#" class="btn btn-link toggleModal" data-id="{$op.idopinion}" data-toggle="modal" data-target="#modal-edit">
                <span class="fa fa-edit"></span>
            </a>
        </td>
        <td class="text-center">
            <a href="#" class="btn btn-link toggleModal" data-id="{$op.idopinion}" data-toggle="modal" data-target="#modal-delete">
                <span class="fa fa-trash-o"></span>
            </a>
        </td>
    {/if}
</tr>