<h1><?= ContextManager::get("i18n")("e_log_connect")?></h1>
<?php mayDisplayPopup($login_result); ?>
<form method="POST" action="<?= $request->server['request_uri'] ?>">
    <?= renderMagnifikInput($request, array(
        "name" => "username",
        "placeholder" => "e_log_username",
        "autocomplete" => "username",
        "required" => true
    ))?>
    <?= renderMagnifikInput($request, array(
        "type" => "password",
        "placeholder" => "userPwd",
        "autocomplete" => "current-password",
        "required" => true
    ))?>
    <input 
        class="hype"
        type="submit" 
        value="✓ <?= ContextManager::get("i18n")("validate")?>"
        title="<?= ContextManager::get("i18n")("e_log_connect")?>"
    />
</form>
