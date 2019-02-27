<script>
        'use strict';

        var clientURLLibrary = <?php echo json_encode($clientURLLibrary)?>;
        var clientURLUnified = <?php echo json_encode($clientURLUnified)?>;
        var clientURLShout = <?php echo json_encode($clientURLShout)?>;
        var libraryUser = <?php echo json_encode($user_qs)?>;
        var sioURL = <?php echo json_encode($sio_url)?>;
        var i18n = <?php echo json_encode(I18N)?>;
        var lang = <?php echo json_encode(LANG)?>;

        var filter = {
                genreUI : null,
                artistUI : null,
                albumUI : null
        };

        var dataFeeds = {};
        var lib = {};
        var shout = {};
        </script>
