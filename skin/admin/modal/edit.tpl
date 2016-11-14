<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="forms-plugins-comment" method="post" action="{$smarty.server.REQUEST_URI}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{#edit_opinion#|ucfirst}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>{#opinion_name#|ucfirst}&nbsp;:</th>
                                    <td id="name"></td>
                                </tr>
                                <tr>
                                    <th>{#opinion_email#|ucfirst}&nbsp;:</th>
                                    <td id="email"></td>
                                </tr>
                                <tr>
                                    <th>{#opinion_note#|ucfirst}&nbsp;:</th>
                                    <td id="opinion-rating"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-7">
                            <div class="form-group">
                                <label for="msg_opinion">{#opinion_content#|ucfirst}*&nbsp;:</label>
                                <textarea id="msg_opinion" name="msg_opinion" class="form-control"></textarea>
                                <p class="help-block">{#fields_requested#|ucfirst}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="idopinion" name="id" value="" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">{#cancel#|ucfirst}</button>
                    <input type="submit" class="btn btn-primary" value="{#save#|ucfirst}" />
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->