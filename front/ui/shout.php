<div id='shoutContainer' style='max-height:0'>
    <div id='shoutNotification'>
        <?php echo i18n("nowPlaying")?>
    </div>
    <div id='shoutNotificationOut'>
        <i class="fas fa-forward"></i>
    </div>
    <div class='shout'>
        <a title='<?php echo i18n("playOnYT")?>' target='_blank'>
            <div class='imgLoader cover'>
                <img onload="imgLoaded(event)" onerror="brokenImg(event)"/>
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
        <label class='mute'>
            <input id='muzzleShout' type='checkbox' onclick="toggleShoutSound(event)" autocomplete="off">
            <i class="fas fa-bell" title-on='<?php echo i18n("playSound")?>' title-off='<?php echo i18n("muteNotif")?>'></i>
        </label>
    </div>
</div>