<form method="POST" autocomplete="off" enctype="multipart/form-data" action="<?= $_SERVER["REQUEST_URI"] ?>">
  <input name="MAX_FILE_SIZE" type="hidden"  value="<?= getFileUploadLimit() ?>" autocomplete="off" />
  <input name="<?= constant("MUSIC_LIB_UPLOAD_FILE_NAME") ?>" type="file" value="" accept=".json" required autocomplete="off" />
  <input autocomplete="current-password" name="password" type="password" placeholder="<?= i18n("userPwd")?>" required autocomplete="off" /> 
  <input class="hype" type="submit" value="<?= i18n("sendFile")?>" autocomplete="off" />
</form>