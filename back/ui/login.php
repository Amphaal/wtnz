<?php if(!empty($login_result)) {var_dump($login_result);}?>

<div style="display: flex; flex-direction:column;align-items:center;">
    <h1><?php echo i18n("e_log_connect")?></h1>
    <form method="POST" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
        <input 
            name="username" 
            placeholder="<?php echo i18n("e_log_username")?>" 
            autocomplete="username"
            required 
            value="<?php echo isset($_POST['username']) ? $_POST['username'] : ""; ?>"
        />
        <div>
            <input 
                name="password" 
                type="password" 
                placeholder="<?php echo i18n("userPwd")?>" 
                autocomplete="current-password"
                required 
            />
        </div>
        <br/>
        <input 
            type="submit" 
            value="<?php echo i18n("e_log_connect")?>"
        />
    </form>
</div>