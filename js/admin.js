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

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

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
var MC_plugins_opinion = (function($, window, document, undefined){
    /**
     * Set values input in update modal
     * @param id
     * @param baseadmin
     * @param getlang
     */
    function setInput(id,baseadmin,getlang){
        $.nicenotify({
            ntype: "ajax",
            uri: '/'+baseadmin+'/plugins.php?name=opinion&getlang='+getlang+'&action=edit&edit='+id,
            typesend: 'get',
            dataType: 'json',
            successParams: function(d) {
                if(d != undefined) {
                    var id = d['idopinion'].replace('\t', '').replace('\t', ''),
                        name = d['pseudo_opinion'].replace('\t', '').replace('\t', ''),
                        email = d['email_opinion'].replace('\t', '').replace('\t', ''),
                        msg = d['msg_opinion'].replace('\t', '').replace('\t', ''),
                        rating = d['rating_opinion'].replace('\t', '').replace('\t', '');

                    $('#idopinion').val(id);
                    $('#name').text(name);
                    $('#email').text(email);
                    $('#msg_opinion').val(msg);

                    var ratingStars = '',
                        i;
                    for(i=1;i <= rating;i++){
                        ratingStars += '<span class="fa fa-star text-primary"></span>';
                    }
                    for(i=1;i <= 5-rating;i++){
                        ratingStars += '<span class="fa fa-star text-muted"></span>';
                    }

                    $('#opinion-rating').append(ratingStars);
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
    function save(id,baseadmin,getlang){
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
                $.nicenotify({
                    ntype: "submit",
                    uri: '/'+baseadmin+'/plugins.php?name=opinion&getlang='+getlang+'&action=edit',
                    typesend: 'post',
                    idforms: $(f),
                    resetform: true,
                    beforeParams: function() {
                        msg = $('#msg_opinion').val();
                    },
                    successParams: function (d) {
                        $.nicenotify.initbox(d.notify, {display: true});
                        console.log(d);
                        $('#op_'+d.result[':id']).find('.op_content > a').popover('destroy').attr('data-content',msg).data('content',msg).popover();
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
    function delete_data(id,baseadmin,getlang){
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
                $.nicenotify({
                    ntype: "submit",
                    uri: '/'+baseadmin+'/plugins.php?name=opinion&getlang='+getlang+'&action=delete',
                    typesend: 'post',
                    idforms: $('#delete_form'),
                    resetform: true,
                    successParams: function(d) {
                        $('#modal-delete').modal('hide');
                        $.nicenotify.initbox(d.notify,{ display:true });
                        window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);

                        if(d.statut && d.result) {
                            $('#op_'+d.result[':id']).remove();
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
    function validate(id,baseadmin,getlang){
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
                $.nicenotify({
                    ntype: "submit",
                    uri: '/'+baseadmin+'/plugins.php?name=opinion&getlang='+getlang+'&action=validate',
                    typesend: 'post',
                    idforms: $('#validate_form'),
                    resetform: true,
                    successParams: function(d) {
                        $('#modal-validate').modal('hide');
                        $.nicenotify.initbox(d.notify,{ display:true });
                        window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);

                        if(d.statut && d.result) {
                            $('#op_'+d.result[':id']).remove();
                        }
                    }
                });
            }
        });
    }

    /**
     * public function
     */
    return {
        run:function(baseadmin,getlang,iso,edit) {
            $(function(){
                $(document).on('click','a.toggleModal',function(){
                    if($(this).data('id')){
                        var id = $(this).data('id');

                        //Edit Modal
                        if($(this).data('target') == '#modal-edit') {
                            $('#forms-plugins-comment')[0].reset();
                            $('#opinion-rating').empty();
                            setInput(id,baseadmin,getlang);
                            save(id,baseadmin,getlang);
                        }
                        else if($(this).data('target') == '#modal-validate') {
                            $('#validate_form input[name="id"]').val(id);
                            validate(id,baseadmin,getlang);
                        }
                        else {
                            $('#delete_form input[name="id"]').val(id);
                            delete_data(id,baseadmin,getlang);
                        }
                    }
                });
            })
        }
    }
})(jQuery, window, document);