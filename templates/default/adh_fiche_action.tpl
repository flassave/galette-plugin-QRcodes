{if $login->isAdmin() or $login->isStaff()}
<li>
    <img src="{path_for name="show_qrcode" data=["type" => {_T string="phone" domain="qrcodes_routes"}, "id" => $member->id]}?rand={$time}" class="picture" alt=""/> <strong>Tel.</strong>
</li>
<li>
    <img src="{path_for name="show_qrcode" data=["type" => {_T string="email" domain="qrcodes_routes"}, "id" => $member->id]}?rand={$time}" class="picture" alt=""/> <strong>Mail</strong>
</li>
<li>
    <a class="button" href="{path_for name="generate_qrcode_member" data=["id" => $member->id]}" id="btn_plugins_QRcodes">{_T string="QR codes" domain="qrcodes"}</a>
</li>
{/if}
