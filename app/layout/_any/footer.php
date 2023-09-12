<footer>
    <div id='credits'>
        <span>LVNWL 2018-<?= date("Y") ?></span>
        <span>&nbsp;-&nbsp;</span>
        <span style="color:black">0.3.0 Alpha</span>
        <span>&nbsp;-&nbsp;</span>
        <a href='https://www.linkedin.com/in/guillaumevara/' title="<?= i18n("devLinkedin")?>" target="_blank" rel="noopener">
            <img src='/public/images/linkedin.png' alt="<?= i18n("devLinkedin")?>"/>
        </a>
    </div>
    <div id="langs">
        <?php 
        
        $curLang = I18nSingleton::getInstance()->getLang();
        
        foreach(getFilesInFolder('public/images/flags') as $file) { 
            $bn =  basename($file, ".svg");
            $isCurrentLang = $bn == $curLang;
        ?>
        <label 
            <?php if(!$isCurrentLang) {?> title="<?= i18n("switch_lang");?>" <?php } ?>
            data-lang="<?= $bn; ?>" 
            class="<?php if(!$isCurrentLang) { echo "clickable unselected"; }?>" 
            onclick="changeLang(event)"
        >
            <img src="<?= constant("WEB_APP_ROOT") . $file; ?>" />
        </label>
        <?php } ?>
    </div>
</footer>