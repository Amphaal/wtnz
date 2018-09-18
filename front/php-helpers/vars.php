<script>
        'use strict';

        var clientURLLibrary = <?php echo json_encode($clientURLLibrary)?>;
        var clientURLShout = <?php echo json_encode($clientURLShout)?>;
        var filter = {
                genreUI : null,
                artistUI : null,
                albumUI : null
        };

        var dataFeeds = {};
        var lib = {};
        </script>
