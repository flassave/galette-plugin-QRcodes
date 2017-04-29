<li>
{if $login->isAdmin() or $login->isStaff()}
    <img src="{$galette_base_path}{$QRcodes_dir}picture.php?id_adh={$member->id}&amp;code=phone&amp;rand={$time}" class="picture" alt=""/> <strong>Tel.</strong>
{/if}
</li>
<li>
{if $login->isAdmin() or $login->isStaff()}
    <img src="{$galette_base_path}{$QRcodes_dir}picture.php?id_adh={$member->id}&amp;code=mail&amp;rand={$time}" class="picture" alt=""/> <strong>Mail</strong>
{/if}
</li>
<li>
{if $login->isAdmin() or $login->isStaff()}
	<a class="button" href="{$galette_base_path}{$QRcodes_dir}QRcodes.php?id_adh={$member->id}&enr=1" id="btn_plugins_QRcodes">{_T string="QR codes"}</a>
{/if}
</li>
