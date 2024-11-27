<div style="display: flex; flex-direction: column; align-items: center">
  <form 
    method="POST" 
    autocomplete="off" 
    enctype="multipart/form-data" 
    action="<?= ContextManager::get("REQUEST")->server['request_uri'] ?>"
  >
    <?php /*<input name="MAX_FILE_SIZE" type="hidden"  value="<?= getFileUploadLimit() ?>" autocomplete="off" /> */ ?>
    <input name="<?= MUSIC_LIB_UPLOAD_FILE_NAME ?>" type="file" value="" accept=".json,.zmlib" required autocomplete="off" />
    <input autocomplete="current-password" name="password" type="password" placeholder="<?= i18n("userPwd")?>" required autocomplete="off" /> 
    <input class="hype" type="submit" value="<?= i18n("sendFile")?>" autocomplete="off" />
  </form>
  <span style="color: grey; font-size: .8em"><?= i18n('or') ?></span>
  <br/>
  <a target="_blank" href="<?= COMPANION_APP_GITHUB_LATEST_RELEASE_URL ?>">
    <button style="padding: .25em .5em" class="hype" value="<?= i18n("sendFile")?>">
      <i class="fa-brands fa-github"></i>
      <span><?= i18n("uploadWithCompanionApp") ?></span>
    </button>
  </a>
</div>