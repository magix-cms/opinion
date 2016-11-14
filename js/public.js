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
 * Date: 10-11-16
 * License: Dual licensed under the MIT or GPL Version
 */
var Mc_plugins_opinion = (function ($, undefined) {
    //Fonction Private
    function add(iso,currentUrl){
        $.nicenotify.notifier = {
            box:"",
            elemclass : '.mc-message-opinion'
        };
        $("#add-opinion").validate({
            onsubmit: true,
            event: 'submit',
            submitHandler: function(form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: '/'+iso+'/opinion/',
                    typesend: 'post',
                    idforms: $(form),
                    noticedata: {product_link:currentUrl},
                    resetform:true,
                    successParams:function(d){
                        $('#modal-opinion').modal('hide');
                        $.nicenotify.initbox(d.notify,{display:true});
                        window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);
                    }
                });
                return false;
            }
        });
    }
    return {
        //Fonction Public
        run:function (iso,currentUrl) {
            add(iso,currentUrl);
        }
    };
})(jQuery);