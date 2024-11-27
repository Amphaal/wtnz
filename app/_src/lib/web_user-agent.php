<?php

////////////////
// OS Related //
////////////////

function getOS() { 

    $os_array = [
        '/mac/i' =>  'Mac',
        '/win/i' =>  'Windows',
        '/ip/i' =>  'iOS',
        '/android/i' =>  'Android'
    ];

    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, ContextManager::get("REQUEST")->header['user-agent'])) 
            return $value;
    }   

    return null;
}

$_DF_OS = [
    "osx" => "Mac",
    "win" => "Windows"
];

$_OS_DF = array_flip($_DF_OS);

function fromDownloadFolderToOS($folder) {
    global $_DF_OS;
    return array_key_exists($folder, $_DF_OS) ? $_DF_OS[$folder] : null;
}

function fromOSToDownloadFolder($os) {
    global $_OS_DF;
    return array_key_exists($os, $_OS_DF) ? $_OS_DF[$os] : null;
}

////////////////////
// END OS Related //
////////////////////
