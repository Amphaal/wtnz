<?php echoFilesOfFolder($documentRoot . "/layout/explorer/css"); ?>
<?= cbacToCss($qs_user, UserDb::fromProtected($qs_user)["customColors"]) ?>
