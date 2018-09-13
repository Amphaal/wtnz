<?php foreach(getFilesInFolder('front/css') as $path) { ?>
    <link rel="stylesheet" href="<?php echo $path; ?>">
<?php } ?>