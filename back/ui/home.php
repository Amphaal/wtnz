<div id="accountManagement">
    <?php if($iul) {?>
        <span id="ProfilePicEditor" title="<?php echo i18n("updateProfilePic")?>">
            <input class="PPPicker" type="file" name="profile_pic" accept="image/*">
            <div class="imgHolder">
                <i class="fas fa-user ph"></i>
                <img class="pp" src="" />
            </div>
        </span>
    <?php } ?>
    <div>
        <?php if($iul) {?>
        <div><?php echo i18n("welcome_back",  getCurrentUserLogged()) ?></div>
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
                <?php include "back/ui/login.php" ?>
                <button <?php _btnLink("/wtnz/manage/create")?>>
                    <?php echo i18n("log_createAccount")?>
                </button>
            <?php } ?>
        </div>
    </div>
    <?php if($iul) {?>
        <div>
            <span><?php echo i18n("obtainApp")?></span>
            <div style='margin:.5rem; display: flex; flex-direction: column'>
                <?php foreach($dd_folders as $folder) {?>
                    <button <?php _btnLink("/wtnz/download/" . $folder, true) ?>>
                        <?php echo i18n("downloadFeeder", fromDownloadFolderToOS($folder))?>
                    </button>
                <?php }?>
            </div>
        </div>
    <?php } ?>
</div>