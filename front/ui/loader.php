<div id='loader-container'>
    <div id='loader' class='animated fadeIn'>
        <?php 
            // not using chrome
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') === false) { ?>
                <div id='useChrome' class='loading-text'>
                    <img src='front/img/chrome.png'/>
                    <div>Works best with</div>
                </div>
         <?php } else { ?>
        <div class='loading-text'>Loading...</div>
         <?php } ?>
        <div id='loader-bar'></div>
    </div>
</div>