<div id='banner-side'>
    <label class="clickable" title="<?= ContextManager::get("i18n")("users_profile", $qs_user)?>">
        <input id='showProfile' type='checkbox' onchange="toggleProfile(event)" autocomplete="off">
        <?php if($expectedProfilePic) {?>
        <img class='profilepic' src="<?= $expectedProfilePic ?>">
        <?php } else {?>
        <i class="fas fa-user"></i>
        <?php } ?>
    </label> 
    <label class="clickable" title="<?= ContextManager::get("i18n")("feed")?>">
        <input id='showFeed' type='checkbox' onchange="toggleFeed(event)" autocomplete="off">
        <i class="fas fa-newspaper"></i>
    </label>
    <label class="clickable" title="<?= ContextManager::get("i18n")("stats")?>">
        <input id='showStats' type='checkbox' onchange="toggleStats(event)" autocomplete="off">
        <i class="fas fa-chart-pie"></i>
    </label>
    <?php include $documentRoot . "/layout/explorer/components/connect_btn.php" ?>
</div>