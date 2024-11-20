<footer>
    <div id='credits'>
        <span><?= constant("APP_NAME")?> 2018-<?= date("Y") ?></span>
        <span>&nbsp;-&nbsp;</span>
        <span style="color:black">0.8.4 Beta</span>
        <span>&nbsp;-&nbsp;</span>
        <a href='https://www.linkedin.com/in/guillaumevara/' title="<?= ContextManager::get("i18n")("devLinkedin")?>" target="_blank" rel="noopener">
            <img src='/public/images/linkedin.png' alt="<?= ContextManager::get("i18n")("devLinkedin")?>"/>
        </a>
    </div>
    <div id="langs">
        <?php 
        
        $curLang = ContextManager::get("i18nS")->getLang();
        
        foreach(getFilesInFolder($publicFilesRoot . '/images/flags') as $file) { 
            $bn =  basename($file, ".svg");
            $isCurrentLang = $bn == $curLang;
        ?>
        <label 
            <?php if(!$isCurrentLang) {?> title="<?= ContextManager::get("i18n")("switch_lang");?>" <?php } ?>
            data-lang="<?= $bn; ?>" 
            class="<?php if(!$isCurrentLang) { echo "clickable unselected"; }?>" 
            onclick="changeLang(event)"
        >
            <img src="<?= constant("WEB_APP_ROOT") . 'public/' . str_replace($publicFilesRoot.'/', '', $file); ?>" />
        </label>
        <?php } ?>
    </div>
</footer>