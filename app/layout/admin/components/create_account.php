<div id="accountCreation">
    <h1><?= i18n("e_log_createAccount")?></h1>
    <?= _popup($acr); ?>
    <form class="loginRack" method="POST" autocomplete="off" action="<?= $_SERVER["REQUEST_URI"] ?>">
        <?= _magnifikInput(array(
            "name" => "username",
            "placeholder" => "e_log_username",
            "required" => true,
            "autocomplete" => "username"
        ), $rules)?>    
        <?= _magnifikInput(array(
            "type" => "password",
            "placeholder" => "userPwd",
            "required" => true,
            "autocomplete" => "current-password"
        ), $rules)?>    
        <?= _magnifikInput(array(
            "type" => "email",
            "placeholder" => "e_log_email",
            "required" => true
        ))?>
        <input
            class="hype"
            type="submit" 
            value="✓ <?= i18n("validate")?>"
            title="<?= i18n("e_log_createAccount")?>"
        />
    </form>
</div>