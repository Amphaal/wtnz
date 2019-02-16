<html>
    <body style='display:flex; justify-content : center; align-items: center; flex-direction:column'>
        <div style='font-size:3em'><?php echo i18n("thisis")?></div>
        <?php if(isUserLogged()) {?>
            <div><?php echo i18n("welcome",  getCurrentUserLogged()) ?></div>
        <?php } ?>
        <div style='margin:.5rem'>
            <?php if(isUserLogged()) {?>
                <button onclick="location.href='<?php echo  getCurrentUserLogged() ?>'"><?php echo i18n("log_accessMyLib")?></button>
                <button onclick="location.href='/wtnz/manage/disconnect'"><?php echo i18n("log_disconnect")?></button>
            <?php } else { ?>
                <button onclick="location.href='/wtnz/manage/create'"><?php echo i18n("log_createAccount")?></button>
                <button onclick="location.href='/wtnz/manage/connect'"><?php echo i18n("e_log_connect")?></button>
            <?php } ?>
        </div>
        <?php if(isUserLogged()) {?>
        <div style='flex:.5'></div>
        <span><?php echo i18n("obtainApp")?></span>
        <div style='margin:.5rem'>
            <button onclick="location.href='/wtnz/download/osx'"><?php echo i18n("downloadFeeder", "Mac")?></button>
            <button onclick="location.href='/wtnz/download/win'"><?php echo i18n("downloadFeeder", "Windows")?></button>
        </div>
        <?php } ?>
    </body>
</html>