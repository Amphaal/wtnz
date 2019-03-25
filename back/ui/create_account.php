<div id="accountCreation">
    <h1><?php echo i18n("e_log_createAccount")?></h1>
    <?php echo _popup($acr); ?>
    <form class="loginRack" method="POST" autocomplete="off" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
        <?php echo _magnifikInput(array(
            "name" => "username",
            "placeholder" => "e_log_username",
            "required" => true,
            "autocomplete" => "username"
        ), $rules)?>    
        <?php echo _magnifikInput(array(
            "type" => "password",
            "placeholder" => "userPwd",
            "required" => true,
            "autocomplete" => "current-password"
        ), $rules)?>    
        <?php echo _magnifikInput(array(
            "type" => "email",
            "placeholder" => "e_log_email",
            "required" => true
        ))?>
        <input
            class="hype"
            type="submit" 
            value="✓ <?php echo i18n("validate")?>"
            title="<?php echo i18n("e_log_createAccount")?>"
        />
    </form>
</div>