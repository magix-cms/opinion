{extends file="mail/layout.tpl"}
<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
{block name='body:content'}
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
        <tr>
            <td valign="top">
                <!-- Tables are the most common way to format your email consistently. Set your table widths inside cells and in most cases reset cellpadding, cellspacing, and border to zero. Use nested tables as a way to space effectively in your message. -->
                <table cellpadding="0" cellspacing="0" border="0" align="center">
                    <tr>
                        <td width="800" style="background: #222222;padding:5px;" valign="top">
                            <!-- Gmail/Hotmail image display fix -->
                            <a href="http://www.in-vini.com/" target ="_blank" title="in-vini">
                                <img class="image_fix" src="{geturl}/skin/{template}/img/logo-invini.png" alt="in-vini" title="in-vini" />
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td width="800" style="background: #FFFFFF;padding:5px;" valign="top">
                            <p>Un nouvel avis est disponible dans l'administration en validation</p>
                        </td>
                    </tr>
                </table>
                <!-- End example table -->
                {*
                <!-- Working with telephone numbers (including sms prompts).  Use the "mobile" class to style appropriately in desktop clients
                versus mobile clients. -->
                <span class="mobile_link">123-456-7890</span>
                *}
            </td>
        </tr>
    </table>
{/block}
<!-- End of wrapper table -->