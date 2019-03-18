<div id='shoutContainer' class="subFrame">
    <div id='shoutNotification'>
        <?php echo i18n("nowPlaying")?>
    </div>
    <div class='shout'>
        <a title='<?php echo i18n("playOnYT")?>' rel="noopener">
            <div id="shoutImgLoader" class='imgLoader cover'>
                <img onload="imgLoaded(event)" onerror="brokenImg(event)" alt=""/>
                <i class="fab fa-youtube"></i>
            </div>
        </a>
        <div class='albumDesc'>
            <div>
                <div class='name'></div>
                <div class='meta'></div>
            </div>
        </div>
        <div class='timeline'></div>
        <label class='mute clickable'>
            <input id='muzzleShout' type='checkbox' onchange="toggleShoutSound(event)" autocomplete="off">
            <i class="fas fa-bell" title-on='<?php echo i18n("playSound")?>' title-off='<?php echo i18n("muteNotif")?>'></i>
        </label>
    </div>
</div>