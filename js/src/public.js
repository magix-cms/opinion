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
 * Date: 10-11-16
 * License: Dual licensed under the MIT or GPL Version
 */
var Mc_plugins_opinion = (function ($, undefined) {
    return {
        //Fonction Public
        run:function () {
            if($.fn.rating !== undefined) {
                // --- Initiate the star-rating plugin
                $("#rating-star").rating({
                    theme: 'material-icons',
                    emptyStar: '<i class="material-icons">star_border</i>',
                    filledStar: '<i class="material-icons">star</i>',
                    showClear: false,
                    showCaption: false,
                    defaultCaption: ''
                });

                // --- When seletcing a rate with the stars, update the select input
                $('#rating-star').on('rating.change', function(event, value, caption) {
                    $('#rating option').each(function(){
                        if($(this).val() === value) {
                            $(this).prop('selected',true);
                        }
                    });
                });

                // --- When selecting a rate in the select, update the rating stars
                $('#rating').change(function(){
                    $('#rating-star').rating('update', this.value);
                });
            }

            // --- When opening the list of reviews, scroll to the list
            $("#opinions").on("show.bs.collapse", function () {
                var prev = $('#opinions').prev(), tar = prev.position().top + prev.height();
                $('html, body').animate({ scrollTop: (tar - $('#header').height()) }, 500);
            });
        }
    };
})(jQuery);