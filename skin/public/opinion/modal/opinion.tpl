<div class="modal fade" id="modal-opinion" tabindex="-1" role="dialog" aria-labelledby="{#opinion#}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="add-opinion" method="post" action="{$smarty.server.REQUEST_URI}" xmlns="http://www.w3.org/1999/html">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{#show_add_opinion#|ucfirst}</h4>
                </div>
                <div class="modal-body">
                    <p class="help-block">{#opinion_fields_request#|ucfirst}</p>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group ">
                                <label for="pseudo">{#opinion_name#|ucfirst}*&nbsp;:</label>
                                <input type="text" name="opinion[pseudo]" id="pseudo" placeholder="{#ph_opinion_name#|ucfirst}" class="form-control required" required/>
                            </div>
                            <div class="form-group">
                                <label for="email">{#opinion_email#|ucfirst}*&nbsp;:</label>
                                <input type="email" name="opinion[email]" id="email" placeholder="{#ph_opinion_email#|ucfirst}" class="form-control required" required/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label for="rating" class="control-label">{#opinion_note#|ucfirst}*&nbsp;:</label>
                                <input id="rating-star" class="rating form-control" data-size="xs" data-show-caption="false" data-show-clear="false" data-step="1"/>
                                <select class="form-control required" id="rating" name="opinion[rating]" required>
                                    <option value="" disabled selected>{#ph_opinion_choose#|ucfirst}</option>
                                    {for $i=1 to 5}
                                        <option value="{$i}">{$i}</option>
                                    {/for}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="msg_opinion">{#opinion_content#|ucfirst}*&nbsp;:</label>
                        <textarea id="msg_opinion" name="opinion[msg]" class="form-control required" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="opinion[idcatalog]" value="{$product.idcatalog}" class="required" required/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{#modal_cancel#|ucfirst}</button>
                    <input type="submit" class="btn btn-primary" value="{#modal_send#|ucfirst}" />
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

