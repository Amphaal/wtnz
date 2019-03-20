<div>
    <span><?php echo i18n("obtainApp")?></span>
    <div style='margin:.5rem; display: flex; flex-direction: column'>
        <?php foreach($dd_folders as $folder) {?>
            <button <?php _btnLink("/wtnz/download/" . $folder, true) ?>>
                <?php echo i18n("downloadFeeder", fromDownloadFolderToOS($folder))?>
            </button>
        <?php }?>
    </div>
</div>