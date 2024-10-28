<div id="accountCreation">
    <h1><?= ContextManager::get("i18n")("e_log_createAccount")?></h1>
    <?php mayDisplayPopup($acr); ?>
    <form class="loginRack" method="POST" autocomplete="off" action="<?= $request->server['request_uri'] ?>">
        <?= renderMagnifikInput($request, array(
            "name" => "username",
            "placeholder" => "e_log_username",
            "required" => true,
            "autocomplete" => "username"
        ), $rules)?>    
        <?= renderMagnifikInput($request, array(
            "type" => "password",
            "placeholder" => "userPwd",
            "required" => true,
            "autocomplete" => "current-password"
        ), $rules)?>    
        <?= renderMagnifikInput($request, array(
            "type" => "email",
            "placeholder" => "e_log_email",
            "required" => true
        ))?>
        <input
            class="hype"
            type="submit" 
            value="✓ <?= ContextManager::get("i18n")("validate")?>"
            title="<?= ContextManager::get("i18n")("e_log_createAccount")?>"
        />
    </form>
</div>