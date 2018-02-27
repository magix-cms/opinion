/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * Author: Salvatore Di Salvo
 * Copyright: MAGIX CMS
 * Date: 09-11-16
 * License: Dual licensed under the MIT or GPL Version
 */

+function($){
    'use strict';

    /**
     * Set values input in update modal
     * @param id
     * @param baseadmin
     * @param getlang
     */
    function setInput(id){
        $.jmRequest({
            handler: "ajax",
            url: '/'+baseadmin+'/index.php?controller=opinion&action=edit&edit='+id,
            method: 'get',
            dataType: 'json',
            success: function(d) {
                if(d !== undefined) {
                    $('#id_opinion').val(d.id_opinion);
                    $('#name').text(d.pseudo_opinion);
                    $('#email').text(d.email_opinion);
                    $('#msg_opinion').val(d.msg_opinion);
                    $('#filled-star').attr('data-rate',d.rating_opinion);
                }
            }
        });
    }

    /**
     * Save the edited opinion
     * @param id
     * @param baseadmin
     * @param getlang
     */
    function save(id){
        var msg = '';
        $('#forms-plugins-comment').validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                msg_opinion: {
                    required: true
                },
                id: {
                    required: true
                }
            },
            submitHandler: function (f) {
                $.jmRequest({
                    handler: "submit",
                    url: '/'+baseadmin+'/index.php?controller=opinion&action=edit',
                    method: 'post',
                    form: $(f),
                    resetForm: true,
                    beforeSend: function() {
                        msg = $('#msg_opinion').val();
                    },
                    successParams: function (d) {
                        $.jmRequest.initbox(d.notify, {display: true});
                        $('#op_'+d.result['id']).find('.op_content > a').popover('destroy').attr('data-content',msg).data('content',msg).popover();
                        $('#modal-edit').modal('hide');
                    }
                });
                return false;
            }
        });
    }

    /**
     * Remove a record
     * @param id
     * @param baseadmin
     * @param getlang
     */
    function delete_data(id){
        $('#delete_form').validate({
            ignore: [],
            onsubmit: true,
            event: 'submit',
            rules: {
                id: {
                    required: true
                }
            },
            submitHandler: function () {
                $.jmRequest({
                    handler: "submit",
                    url: '/'+baseadmin+'/index.php?controller=opinion&action=delete',
                    method: 'post',
                    form: $('#delete_form'),
                    resetForm: true,
                    success: function(d) {
                        $('#modal-delete').modal('hide');
                        $.jmRequest.initbox(d.notify,{ display:true });
                        window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);

                        if(d.statut && d.result) {
                            $('#op_'+d.result['id']).remove();
                        }
                    }
                });
            }
        });
    }

    /**
     * Validate an opinion
     * @param id
     * @param baseadmin
     * @param getlang
     */
    function validate(id){
        $('#validate_form').validate({
            ignore: [],
            onsubmit: true,
            event: 'submit',
            rules: {
                id: {
                    required: true
                }
            },
            submitHandler: function () {
                $.jmRequest({
                    handler: "submit",
                    url: '/'+baseadmin+'/index.php?controller=opinion&action=validate',
                    method: 'post',
                    form: $('#validate_form'),
                    resetForm: true,
                    success: function(d) {
                        $('#modal-validate').modal('hide');
                        $.jmRequest.initbox(d.notify,{ display:true });
                        window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);

                        if(d.statut && d.result) {
                            $('#op_'+d.result['id']).remove();
                        }
                    }
                });
            }
        });
    }

    /**
     *
     */
    $(window).on('load', function() {
        $('[data-toggle="popover"]').popover();

        if(typeof baseadmin !== 'undefined') {
            $(document).on('click','a.toggleModal',function(){
                if($(this).data('id')){
                    var id = $(this).data('id');

                    //Edit Modal
                    if($(this).data('target') == '#modal-edit') {
                        $('#forms-plugins-comment')[0].reset();
                        setInput(id);
                        save(id);
                    }
                    else if($(this).data('target') == '#modal-validate') {
                        $('#validate_form input[name="id"]').val(id);
                        validate(id);
                    }
                    else {
                        $('#delete_form input[name="id"]').val(id);
                        delete_data(id);
                    }
                }
            });
        }
    });
}(jQuery);