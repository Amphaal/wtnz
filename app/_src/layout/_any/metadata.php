<?php 

$app_description = ContextManager::get("i18n")("app_descr");
$app_icon_href = "/public/images/ico.png";
$app_title = ContextManager::get("title");

?>

<meta charset="UTF-8">
<title><?= $app_title ?></title>
<link rel="icon" type="image/png" href="<?= $app_icon_href ?>" />
<meta name="description" content="<?= $app_description ?>">
<meta name="theme-color" content="#efefef">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta property="og:url" content="<?= $app_icon_href ?>">
<meta property="og:title" content="<?= $app_title ?>">
<meta property="og:description" content="<?= $app_description ?>">
<meta property="og:image" content="<?= $app_icon_href ?>">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="256">
<meta property="og:image:height" content="256">
<meta property="og:type" content="website">
<meta name="twitter:image" content="<?= $app_icon_href ?>">
<meta name="twitter:description" content="<?= $app_description ?>">
<meta name="twitter:title" content="<?= $app_title ?>">
<meta name="twitter:card" content="photo">