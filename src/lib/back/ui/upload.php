<? include_once $_SERVER['DOCUMENT_ROOT'] . "/config/const.php"; ?>

<form method="POST" autocomplete="off" enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
  <input name="MAX_FILE_SIZE" type="hidden"  value="<?php echo getFileUploadLimit() ?>" autocomplete="off" />
  <input name="<?= $expectedUploadedLibraryFilename ?>" type="file" accept=".json" required autocomplete="off" />
  <input autocomplete="current-password" name="password" type="password" placeholder="<?php echo i18n("userPwd")?>" required autocomplete="off" /> 
  <input class="hype" type="submit" value="<?php echo i18n("sendFile")?>" autocomplete="off" />
</form>