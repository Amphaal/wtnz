<style>
 input {
    min-width: 10rem;
 }
 span {
     font-size: 0.8rem;
 }
</style>

<div style="display: flex; flex-direction:column;align-items:center;">
    <h1><?php echo i18n("e_log_createAccount")?></h1>
    <form method="post" autocomplete="nope">
        <input 
            pattern=".{<?php echo $minlen_username . "," . $maxlen_username;?>}" 
            name="username" 
            placeholder="<?php echo i18n("e_log_username")?>" 
            required 
            value="<?php echo isset($_POST['username']) ? $_POST['username'] : ""; ?>"
        />
        <span><?php echo i18n("e_log_rule", $minlen_username, $maxlen_username);?></span> 
        <div>
            <input 
                pattern=".{<?php echo $minlen_password . "," . $maxlen_password;?>}" 
                name="password" 
                type="password" 
                placeholder="<?php echo i18n("userPwd")?>" 
                required 
            />
            <input 
                pattern=".{<?php echo $minlen_password . "," . $maxlen_password;?>}" 
                name="password_r" 
                type="password" 
                placeholder="<?php echo i18n("e_log_retype")?>" 
                required 
            />
            <span><?php echo i18n("e_log_rule", $minlen_password, $maxlen_password);?></span> 
        </div>
        <div>
            <input 
                name="email" 
                type="email" 
                placeholder="<?php echo i18n("e_log_email")?>" 
                required 
                value="<?php echo isset($_POST['email']) ? $_POST['email'] : ""; ?>"
            /> 
            <input 
                name="email_r" 
                type="email" 
                placeholder="<?php echo i18n("e_log_retype")?>" 
                required 
                value="<?php echo isset($_POST['email']) ? $_POST['email'] : ""; ?>"
            />
        </div> 
        <br/>
        <input 
            type="submit" 
            value="<?php echo i18n("e_log_createAccount")?>"
        />
    </form>
</div>