<script>
    var i18n = <?php echo json_encode(I18nSingleton::getInstance()->getDictionary())?>;
    var lang = <?php echo json_encode(I18nSingleton::getInstance()->getLang())?>;
    <?php echoFilesOfFolder('back/template/js')?>
</script>