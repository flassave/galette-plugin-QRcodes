{if $login->isAdmin()}
        <h1 class="nojs">{_T string="QRcodes" domain="qrcodes"}</h1>
        <ul>
            <li><a href="{$galette_base_path}{$QRcodes_dir}includes/t0k4rt-phpqrcode-d213c48/">{_T string="QRcodes generator" domain="qrcodes"}</a></li>
            <li><a href="{$galette_base_path}{$QRcodes_dir}QRcodes.php">{_T string="Create members QRcodes" domain="qrcodes"}</a></li>
        </ul>
{/if}
