<style>
    #intro-users-descr {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        overflow: auto;
        max-height: 33vh;
        padding: .75em;
        gap: .5em;
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
<div style="display:flex; align-items:center; flex-direction: column; width: 100wv">
    <img src="/public/images/ico.png" width="50%"/>
    <h1 style="margin-bottom: 0; text-align: center"><?= ContextManager::get("i18n")("thisis")?></h1>
    <div style="font-size: .75em; color: #3b3b3b; text-align: center">
        <?= ContextManager::get("i18n")("project_shorthand_descr", '<img src="/public/images/itunes.png" width="24px" style="margin: 0 .2em; vertical-align: bottom;" />')?>
    </div>
    <?php if (!empty($users)) { ?>
    <br/>
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
    <?php } ?>
    <div style="margin-top: 1em; display: flex; justify-content: center;">
        <a href="/manage/create">
            <div class="manage_button" style="background-color: #23df0d54; box-shadow: 0px 0px 20px 0px white; color: #245724; border: 1px solid #76cd8c;">
                <i class="fa-solid fa-plus"></i>
                <span><?= ContextManager::get("i18n")("log_createAccount") ?></span>
            </div>
        </a>
    </div>
</div>
