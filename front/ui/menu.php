<div id='banner-side'>
    <label title="<?php echo i18n("my_profile")?>">
        <input id='showProfile' type='checkbox' onclick="toggleProfile(event)" autocomplete="off">
        <?php if($expectedProfilePic) {?>
        <img class='profilepic' src="<?php echo $expectedProfilePic ?>">
        <?php } else {?>
        <i class="fas fa-user"></i>
        <?php } ?>
    </label> 
    <label title="<?php echo i18n("feed")?>">
        <input id='showFeed' type='checkbox' onclick="toggleFeed(event)" autocomplete="off">
        <i class="fas fa-newspaper"></i>
    </label>
    <label title="<?php echo i18n("stats")?>">
        <input id='showStats' type='checkbox' onclick="toggleStats(event)" autocomplete="off">
        <i class="fas fa-chart-pie"></i>
    </label>   
</div>