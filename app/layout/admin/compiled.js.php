<script>
    var i18n = <?= json_encode(I18nSingleton::getInstance()->getDictionary())?>;
    var lang = <?= json_encode(I18nSingleton::getInstance()->getLang())?>;
    <?=FilesOfFolder('back/template/js')?>
</script>