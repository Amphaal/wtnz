<h1><?= i18n("thisis")?></h1>
<style>
    #intro-users-descr {
        display:flex;
        transition: opacity .2s;
    }

    #intro-users-descr:hover {
        opacity: .7;
    }

    #intro-users-descr .user {
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
                $hasProfilePicture = $data["profilePic"];
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

