var i18n = <?= json_encode(ContextManager::get("i18nS")->getDictionary())?>;
var lang = <?= json_encode(ContextManager::get("i18nS")->getLang())?>;
<?php echoFilesOfFolder($sourcePhpRoot . "/layout/admin/js") ?>