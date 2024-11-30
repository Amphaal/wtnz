var i18n = <?= json_encode(I18nHandler::get()->getDictionary(), JSON_PRETTY_PRINT)?>;
var lang = <?= json_encode(I18nHandler::get()->getLang())?>;
<?php echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/admin/js") ?>