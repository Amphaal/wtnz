<h1><?php echo i18n("e_log_connect")?></h1>
<?php echo _popup($login_result); ?>
<form method="POST" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
    <?php echo _magnifikInput(array(
        "name" => "username",
        "placeholder" => "e_log_username",
        "autocomplete" => "username",
        "required" => true
    ))?>
    <?php echo _magnifikInput(array(
        "type" => "password",
        "placeholder" => "userPwd",
        "autocomplete" => "current-password",
        "required" => true
    ))?>
    <input 
        type="submit" 
        value="<?php echo i18n("e_log_connect")?>"
    />
</form>
