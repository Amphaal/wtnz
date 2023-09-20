<h1><?= i18n("thisis")?></h1>
<style>
    #intro-users-descr {
        display: flex;
        gap: .4em;
        align-items: center;
        justify-content: center;
    }

    #intro-users-descr .user:hover, .manage_button:hover {
        opacity: .7;
    }

    #intro-users-descr .user, .manage_button {
        transition: opacity .2s;
        border-radius: 5px;
        background-color: white; 
        padding: .5em; 
        display:flex; 
        gap: .5em; 
        align-items: center; 
        box-shadow: 0px 0px 20px 0px #00000036;
    }    
</style>
<div id="intro-users-descr">
    <?php foreach($users as $username => $data) { ?>
        <a href="/u/<?= $username ?>">
            <div class="user">
                <?php 
                $hasProfilePicture = $data["profilePic"] ?? NULL;
                if($hasProfilePicture != null) {
                    $expectedProfilePic = getPublicUserFolderOf($username) . $hasProfilePicture;
                    ?>
                    <img  style="max-height: 1.5em; max-width: 1.5em" src="<?= $expectedProfilePic ?>">
                <?php } else {?>
                    <i class="fas fa-user"></i>
                <?php } ?>
                <span><?= $username ?></span>
                <i class="fa-solid fa-up-right-from-square" style="font-size: .6em"></i>
            </div>
        </a>
    <?php } ?>
</div>
<div style="margin-top: 1em; display: flex; justify-content: center;">
    <a href="/manage">
        <div class="manage_button" style="background-color: #ffbaba54; box-shadow: 0px 0px 20px 0px white;">
            <i class="fa-solid fa-plus"></i>
            <span><?= i18n("log_createAccount") ?></span>
        </div>
    </a>
</div>

