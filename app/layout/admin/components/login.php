<h1><?= i18n("e_log_connect")?></h1>
<?php mayDisplayPopup($login_result); ?>
<form method="POST" action="<?= $_SERVER["REQUEST_URI"] ?>">
    <?= renderMagnifikInput(array(
        "name" => "username",
        "placeholder" => "e_log_username",
        "autocomplete" => "username",
        "required" => true
    ))?>
    <?= renderMagnifikInput(array(
        "type" => "password",
        "placeholder" => "userPwd",
        "autocomplete" => "current-password",
        "required" => true
    ))?>
    <input 
        class="hype"
        type="submit" 
        value="✓ <?= i18n("validate")?>"
        title="<?= i18n("e_log_connect")?>"
    />
</form>
