<?php if(!empty($acr)) {var_dump($acr);}?>

<div style="display: flex; flex-direction:column;align-items:center;">
    <h1><?php echo i18n("e_log_createAccount")?></h1>
    <form method="POST" autocomplete="off" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
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
            type="submit" 
            value="<?php echo i18n("e_log_createAccount")?>"
        />
    </form>
</div>