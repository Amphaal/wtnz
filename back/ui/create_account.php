<style>
 input {
    min-width: 15rem;
 }
 span {
     font-size: 0.8rem;
 }
</style>

<?php if(!empty($acr)) {var_dump($acr);}?>

<div style="display: flex; flex-direction:column;align-items:center;">
    <h1><?php echo i18n("e_log_createAccount")?></h1>
    <form method="post" autocomplete="off">
        <div>
            <input 
            pattern="<?php echo renHpat($rules['username'])?>" 
            name="username" 
            placeholder="<?php echo i18n("e_log_username")?>" 
            required 
            value="<?php echo PRem('username') ?>"
            />
            <span><?php echo i18n("e_log_rule", $rules['username']["min"], $rules['username']["max"]);?></span> 
        </div>
        <div>
            <input 
                pattern="<?php echo renHpat($rules['password'])?>" 
                name="password" 
                type="password" 
                placeholder="<?php echo i18n("userPwd")?>" 
                required 
            />
            <span><?php echo i18n("e_log_rule", $rules['password']["min"], $rules['password']["max"]);?></span> 
        </div>
        <div>
            <input 
                name="email" 
                type="email" 
                placeholder="<?php echo i18n("e_log_email")?>" 
                required 
                value="<?php echo PRem('email') ?>"
            /> 
        </div> 
        <br/>
        <input 
            type="submit" 
            value="<?php echo i18n("e_log_createAccount")?>"
        />
    </form>
</div>