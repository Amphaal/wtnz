<span id="ProfilePicEditor" title="<?php echo i18n("updateProfilePic")?>">
    <input class="PPPicker" type="file" name="profile_pic" accept="image/*">
    <div class="imgHolder">
        <i class="fas fa-user ph"></i>
        <img class="pp" <?php if($pp_path) {echo "src='" . $pp_path . "'"; }?> />
    </div>
</span>