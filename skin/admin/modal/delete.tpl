<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{#delete_opinion#|ucfirst}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <p><span class="fa fa-warning"></span>&nbsp;{#do_delete#}</p>
                </div>
                <div class="help-block">{#help_text#}</div>
            </div>
            <div class="modal-footer">
                <form id="delete_form" class="delete_form" action="{$smarty.server.REQUEST_URI}" method="post">
                    <input type="hidden" name="id" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{#cancel#|ucfirst}</button>
                    <button type="submit" class="btn btn-danger">{#remove#|ucfirst}</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->