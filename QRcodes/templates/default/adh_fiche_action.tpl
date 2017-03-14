<li>
{if $login->isAdmin() or $login->isStaff()}
	<img src="{$galette_base_path}{$QRcodes_dir}datas/qrcodes/{$member->id}.tel.png"><b>Tel.</b></img>
{/if}
</li>
<li>
{if $login->isAdmin() or $login->isStaff()}
	<img src="{$galette_base_path}{$QRcodes_dir}datas/qrcodes/{$member->id}.mail.png"><b>Mail</b></img>
{/if}
</li>
<li>
{if $login->isAdmin() or $login->isStaff()}
	<a class="button" href="{$galette_base_path}{$QRcodes_dir}QRcodes.php?id_adh={$member->id}&enr=1" id="btn_plugins_QRcodes">{_T string="QR codes"}</a>
{/if}
</li>