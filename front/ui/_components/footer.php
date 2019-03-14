<footer>
    <div id='credits'>
        <span>LVWL 2018-2019</span>
        <span>&nbsp;-&nbsp;</span>
        <span style="color:black">0.2.2 Alpha</span>
        <span>&nbsp;-&nbsp;</span>
        <a href='https://www.linkedin.com/in/guillaumevara/' title="<?php echo i18n("devLinkedin")?>" target="_blank" rel="noopener">
            <img src='front/assets/img/linkedin.png' alt="<?php echo i18n("devLinkedin")?>"/>
        </a>
    </div>
    <div id="langs">
        <?php foreach(getFilesInFolder('front/assets/img/flags') as $file) { 
            $bn =  basename($file, ".svg");
            $isCurrentLang = $bn == I18nSingleton::getInstance()->getLang();
        ?>
        <label 
            <?php if(!$isCurrentLang) {?> title="<?php echo i18n("switch_lang");?>" <?php } ?>
            data-lang="<?php echo $bn; ?>" 
            class="<?php if(!$isCurrentLang) {echo "clickable unselected";}?>" 
            onclick="changeLang(event)"
        >
            <img src="<?php echo $file; ?>" />
        </label>
        <?php } ?>
    </div>
</footer>