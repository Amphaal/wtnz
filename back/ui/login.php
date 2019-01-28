<style>
 input {
    min-width: 10rem;
 }
 span {
     font-size: 0.8rem;
 }
</style>

<div style="display: flex; flex-direction:column;align-items:center;">
    <h1><?php echo i18n("e_log_connect")?></h1>
    <form method="post">
        <input 
            name="username" 
            placeholder="<?php echo i18n("e_log_username")?>" 
            required 
            value="<?php echo isset($_POST['username']) ? $_POST['username'] : ""; ?>"
        />
        <div>
            <input 
                name="password" 
                type="password" 
                placeholder="<?php echo i18n("userPwd")?>" 
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