<div>
    <span><?= i18n("obtainCompanionApp")?></span>
    <div id="dlContainer">
        <?php foreach($dd_folders as $folder) {?>
            <?php /* TODO */ ?>
            <a class="<?= $folder?>" href="/wtnz/download/<?= $folder?>" title="<?= i18n("downloadFeeder", fromDownloadFolderToOS($folder))?>"></a>
        <?php }?>
    </div>
</div>