<?php echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/explorer/css"); ?>
<?= cbacToCss($qs_user, UserDb::fromProtected($qs_user)["customColors"]) ?>
