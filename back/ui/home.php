<div id="accountManagement">
    <?php if($iul) include "back/ui/parts/ppEditor.php" ?>
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
    <?php if($iul) include "back/ui/parts/downloadButtons.php" ?>
</div>
<script>
    let ppe = new PPEditor("ProfilePicEditor");
</script>