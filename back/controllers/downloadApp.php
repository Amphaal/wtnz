<?php 

function routerDownload($action) {
    $initialPath = $_SERVER["DOCUMENT_ROOT"].'/feedtnz/downloads/'.$action;

    switch($action) {
        case 'win':
            break;
        case 'osx':
            $filename = 'FeedTNZSetup.dmg';
            $contentType = 'application/octet-stream';
            break;
    }

    $filepath = $initialPath . "/" . $filename;
    if (!file_exists($filepath)) {
        errorOccured(i18n("e_noDownloadFound"));
    }

    header('Content-Type: '. $contentType);
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    readfile($filepath);
}

function downloadLatestPatch($action) {

    $initialPath = $_SERVER["DOCUMENT_ROOT"].'/feedtnz/packages/'.$action;
    $targetXml = $initialPath . '/Updates.xml';

    if (file_exists($targetXml)) {
        $xml = simplexml_load_file($targetXml);
        $packageName = $xml->PackageUpdate->Name;
        $archiveName = $xml->PackageUpdate->DownloadableArchives;
        $appVersion = $xml->PackageUpdate->Version;
        $pathToArchive = $initialPath . '/'. $packageName . '/'. $appVersion . $archiveName;
        header('Content-type: application/x-7z-compressed');
        header('Content-Disposition: attachment; filename="'.$appVersion . $archiveName.'"');
        readfile($pathToArchive);
    }
    else {
        errorOccured(i18n("e_noDownloadFound"));
    }
}