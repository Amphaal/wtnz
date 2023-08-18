<div id="accountManagement">
    <?php if($iul) {?>
        <div id='EditorWrapper'>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/back/ui/parts/bbEditor.php" ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/back/ui/parts/ppEditor.php" ?>
        </div>
        <div><?php echo i18n("welcome_back",  getCurrentUserLogged()) ?></div>
    <?php } ?>
    <div class="loginRack">
        <?php if($iul) {?>
            <?php if($is_not_my_lib) {?>
                <button class="hype" <?php _btnLink($mylib_loc, true)?>>
                    <i class="fas fa-book"></i>
                    <span><?php echo i18n("log_accessMyLib")?></span>
                </button>
            <?php }?>
            <?php /* TODO */ ?>
            <button class="hype" <?php _btnLink("/wtnz/manage/disconnect", false, true)?>>
                <i class="fas fa-power-off"></i>
                <span><?php echo i18n("log_disconnect")?></span>
            </button>
        <?php } else { ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/back/ui/login.php" ?>
            <hr/>
            <?php /* TODO */ ?>
            <button class="hype" <?php _btnLink("/wtnz/manage/create")?>>
                <i class="fas fa-user-circle"></i>
                <span><?php echo i18n("log_createAccount")?></span>
            </button>
        <?php } ?>
    </div>
    <?php if($iul) include $_SERVER['DOCUMENT_ROOT'] . "/back/ui/parts/downloadButtons.php" ?>
</div>
<?php 
    if($iul) {
        echo "<style>";

        echo cbacToCss(getCurrentUserLogged(), UserDb::mineProtected()["customColors"]);
    
        echo "</style>";
    }
?>
<script>
    let ppe = new PPEditor("ProfilePicEditor");
    let bbe = new BBEditor("bBandEditor");
</script>