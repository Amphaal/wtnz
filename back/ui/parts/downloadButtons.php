<div>
    <span><?php echo i18n("obtainCompanionApp")?></span>
    <div id="dlContainer">
        <?php foreach($dd_folders as $folder) {?>
            <a class="<?php echo $folder?>" href="/wtnz/download/<?php echo $folder?>" title="<?php echo i18n("downloadFeeder", fromDownloadFolderToOS($folder))?>"></a>
        <?php }?>
    </div>
</div>