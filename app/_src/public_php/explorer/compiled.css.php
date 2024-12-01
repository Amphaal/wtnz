<?php echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/explorer/css", true); ?>
<?= cbacToCss($qs_user, UserDb::fromProtected($qs_user)["customColors"]) ?>
