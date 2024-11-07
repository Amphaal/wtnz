<div id="bBandEditor" title="<?= ContextManager::get("i18n")("updateBBand")?>">
    <?= _wAnim(getCurrentUserLogged())?>
    <div class="colorPicker">
        <div class="controls">
            <input class="cancel" type="button" value="✕" title="<?= ContextManager::get("i18n")("cancel")?>"/>
            <input class="validate" type="button" value="✓" title="<?= ContextManager::get("i18n")("validate")?>"/>
        </div>
        <div class="colors">
            <input id="color1" type="color" title="" />
            <input id="color2" type="color" title="" />
            <input id="color3" type="color" title="" />
            <input id="color4" type="color" title="" />
        </div>
    </div>
</div>