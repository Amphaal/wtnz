<script>
        'use strict';

        var clientURLLibrary = <?php echo json_encode($clientURLLibrary)?>;
        var clientURLUnified = <?php echo json_encode($clientURLUnified)?>;
        var clientURLShout = <?php echo json_encode($clientURLShout)?>;
        var libraryUser = <?php echo json_encode($user_qs)?>;
        var sioURL = <?php echo json_encode($sio_url)?>;
        var i18n = <?php echo json_encode(I18nSingleton::getInstance()->getDictionary())?>;
        var lang = <?php echo json_encode(I18nSingleton::getInstance()->getLang())?>;
        var initialRLoaderUrl = <?php echo json_encode($initialRLoaderUrl)?>;
        
        var _discoverFilter = {
                genreUI : null,
                artistUI : null,
                albumUI : null
        };
        var _discoverMixers = {};

        var defFiltStorageKey = "_discoverSorter";
        var _discoverSorter = localStorage.getItem(defFiltStorageKey) || "count:desc";
        var _defaultSorters = {
                count : "desc", 
                order : "asc"
        };
        var _sortersIconAdapter = {
                order : "alpha",
                count : "amount",
                asc : "up",
                desc : "down" 
        };

        var _appDataFeeds = {};
        var _currentShout = {};
        var _currentShoutDWorth = false;

        var _rLoader = null;
</script>
