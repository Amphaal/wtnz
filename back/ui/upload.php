<form method="POST" autocomplete="off" enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
  <input name="MAX_FILE_SIZE" type="hidden"  value="<?php echo getFileUploadLimit() ?>" autocomplete="off" />
  <input name="wtnz_file" type="file" accept=".json" required autocomplete="off" />
  <br/>
  <input name="password" type="password" placeholder="<?php echo i18n("userPwd")?>" required autocomplete="off" /> 
  <input type="submit" value="<?php echo i18n("sendFile")?>" autocomplete="off" />
</form>