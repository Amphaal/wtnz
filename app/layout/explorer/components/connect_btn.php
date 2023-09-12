<div id="<?= uniqid() ?>" class="connect-side<?php if($isLogged) {echo "\"";} else {echo ' notif" data-notif="!"';} ?>>
    <label class='clickable' title="<?= i18n("e_log_home")?>" onclick="hNavigate()">
        <i class="fas fa-sign-in-alt"></i>
    </label>
</div>