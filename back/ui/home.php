<div id="accountManagement">
    <?php if($iul) {?>
        <div><?php echo i18n("welcome",  getCurrentUserLogged()) ?></div>
    <?php } ?>
    <div class="loginRack">
        <?php if($iul) {?>
            <?php if(getLocation("ThisLibrary") != $mylib_loc) {?>
                <button <?php _btnLink($mylib_loc, true)?>>
                    <?php echo i18n("log_accessMyLib")?>
                </button>
            <?php }?>
            <button <?php _btnLink("/wtnz/manage/disconnect", false, true)?>>
                <?php echo i18n("log_disconnect")?>
            </button>
        <?php } else { ?>
            <button <?php _btnLink("/wtnz/manage/connect")?>>
                <?php echo i18n("e_log_connect")?>
            </button>
            <button <?php _btnLink("/wtnz/manage/create")?>>
                <?php echo i18n("log_createAccount")?>
            </button>
        <?php } ?>
    </div>
    <?php if($iul) {?>
        <span><?php echo i18n("obtainApp")?></span>
        <div style='margin:.5rem; display: flex; flex-direction: column'>
            <?php foreach($dd_folders as $folder) {?>
                <button <?php _btnLink("/wtnz/download/" . $folder, true) ?>>
                    <?php echo i18n("downloadFeeder", fromDownloadFolderToOS($folder))?>
                </button>
            <?php }?>
        </div>
    <?php } ?>
</div>