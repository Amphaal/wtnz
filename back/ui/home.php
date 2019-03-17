<div id="accountManagement">
    <?php if($iul) {?>
        <div><?php echo i18n("welcome",  getCurrentUserLogged()) ?></div>
    <?php } ?>
    <div class="loginRack">
        <?php if($iul) {?>
            <?php if(getLocation("ThisLibrary") != $mylib_loc) {?>
                <a no-xhttp href="<?php echo $mylib_loc ?>"><?php echo i18n("log_accessMyLib")?></a>
            <?php }?>
            <a href="/wtnz/manage/disconnect"><?php echo i18n("log_disconnect")?></a>
        <?php } else { ?>
            <a href="/wtnz/manage/connect"><?php echo i18n("e_log_connect")?></a>
            <a href="/wtnz/manage/create"><?php echo i18n("log_createAccount")?></a>
        <?php } ?>
    </div>
    <?php if($iul) {?>
        <span><?php echo i18n("obtainApp")?></span>
        <div style='margin:.5rem; display: flex; flex-direction: column'>
            <?php foreach($dd_folders as $folder) {?>
                <a no-xhttp href="/wtnz/download/<?php echo $folder ?>"><?php echo i18n("downloadFeeder", fromDownloadFolderToOS($folder))?></a>
            <?php }?>
        </div>
    <?php } ?>
</div>