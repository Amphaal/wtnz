<div id="accountManagement">
    <?php if(isUserLogged()) {?>
        <div><?php echo i18n("welcome",  getCurrentUserLogged()) ?></div>
    <?php } ?>
    <div class="loginRack">
        <?php if(isUserLogged()) {?>
            <?php if(getLocation("ThisLibrary") != getLocation("MyLibrary")) {?>
                <a no-xhttp href="<?php echo getLocation("MyLibrary") ?>"><?php echo i18n("log_accessMyLib")?></a>
            <?php }?>
            <a href="/wtnz/manage/disconnect"><?php echo i18n("log_disconnect")?></a>
        <?php } else { ?>
            <a href="/wtnz/manage/connect"><?php echo i18n("e_log_connect")?></a>
            <a href="/wtnz/manage/create"><?php echo i18n("log_createAccount")?></a>
        <?php } ?>
    </div>
    <?php if(isUserLogged()) {?>
        <span><?php echo i18n("obtainApp")?></span>
        <div style='margin:.5rem; display: flex; flex-direction: column'>
            <a no-xhttp href="/wtnz/download/osx"><?php echo i18n("downloadFeeder", "Mac")?></a>
            <a no-xhttp href="/wtnz/download/win"><?php echo i18n("downloadFeeder", "Windows")?></a>
        </div>
    <?php } ?>
</div>