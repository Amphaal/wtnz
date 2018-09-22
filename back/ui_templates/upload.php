<form enctype="multipart/form-data" method="post" autocomplete="off" >
  <input name="MAX_FILE_SIZE" type="hidden"  value="<?php echo getFileUploadLimit() ?>" autocomplete="off" />
  <input name="wtnz_file" type="file" accept=".json" required autocomplete="off" />
  <br/>
  <input name="password" type="password" placeholder="Mot de passe utilisateur" required autocomplete="off" /> 
  <input type="submit" value="Envoyer le fichier" autocomplete="off" />
</form>