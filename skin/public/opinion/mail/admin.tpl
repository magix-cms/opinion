{extends file="opinion/mail/layout.tpl"}
<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
{block name='body:content'}
    <!-- move the above styles into your custom stylesheet -->
    <table align="center" class="container content float-center">
        <tbody>
        <tr>
            <td>
                <table class="spacer">
                    <tbody>
                    <tr>
                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                    </tr>
                    </tbody>
                </table>
                <table class="row">
                    <tbody>
                    <tr>
                        <td class="small-12 large-12 columns first last">
                            <table>
                                <tr>
                                    <td>
                                        <p>{#title_mail_admin#|ucfirst}</p>
                                    </td>

                                    <td class="expander"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="small-12 large-12 first last">
                            <table class="spacer spacer-hr">
                                <tbody>
                                <tr>
                                    <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="small-12 large-6 columns first last">
                            <table>
                                <tr>
                                    <td>
                                        <p><strong>Nom:</strong> {$opinion.pseudo}</p>
                                        <p><strong>Email:</strong> {$opinion.email}</p>
                                        <p><strong>Vote:</strong> {$opinion.rating}&thinsp;/&thinsp;5</p>
                                        <p><strong>Message:</strong></p>
                                        <p>{$opinion.msg}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
{/block}
<!-- End of wrapper table -->