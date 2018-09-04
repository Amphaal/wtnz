<form enctype="multipart/form-data" method="post">
  <input name="MAX_FILE_SIZE" type="hidden"  value="<?php echo getFileUploadLimit() ?>" />
  <input name="wtnz_file" type="file" accept=".json" required />
  <br/>
  <input name="password" type="password" placeholder="Mot de passe utilisateur" required /> 
  <input type="submit" value="Envoyer le fichier" />
</form>