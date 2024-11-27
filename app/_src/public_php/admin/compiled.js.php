var i18n = <?= json_encode(I18nSingleton::getInstance()->getDictionary())?>;
var lang = <?= json_encode(I18nSingleton::getInstance()->getLang())?>;
<?php echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/admin/js") ?>