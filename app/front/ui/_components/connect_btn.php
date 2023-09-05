<div id="<?php echo uniqid() ?>" class="connect-side<?php if($isLogged) {echo "\"";} else {echo ' notif" data-notif="!"';} ?>>
    <label class='clickable' title="<?php echo i18n("e_log_home")?>" onclick="hNavigate()">
        <i class="fas fa-sign-in-alt"></i>
    </label>
</div>