<div class="modal fade" id="modal-validate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{#validate_opinion#|ucfirst}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <p><span class="fa fa-info"></span>&nbsp;{#do_validate#|ucfirst}</p>
                </div>
            </div>
            <div class="modal-footer">
                <form id="validate_form" class="validate_form" action="{$smarty.server.REQUEST_URI}" method="post">
                    <input type="hidden" name="id" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{#cancel#|ucfirst}</button>
                    <button type="submit" class="btn btn-success">{#validate#|ucfirst}</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->