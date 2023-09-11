<?php 

include_once $_SERVER['DOCUMENT_ROOT'] . "/app/back/helpers/Slugifier.php";

class DataGenerator {

    public function __construct($username, &$lib = null) {  

        $this->_username = $username;
        $this->_iTargetPath = getInternalUserFolder($username) . "/";

        if($lib != null) {
            $this->_lib = $lib;
        } else {
            $file_content = file_get_contents($this->_iTargetPath . getCurrentLibraryFileName());
            $this->_lib = json_decode($file_content, true);
        }
    }

    public function generateFiles($prettyPrint = false) {
        $this->_generateData();
        foreach(self::$_outputTargets as $target) {
            $this->_saveData($target, $this->_cache[$target], $prettyPrint);
        }
    }

    public function getDataPart($target) {
        return $this->$target($this->_lib);
    }

    public function generateUnifiedFile($prettyPrint = false) {
        $this->_generateData();
        $package = array();
        foreach(self::$_outputTargets as $target) {
            $package[$target] = $this->_cache["_".$target];
        }
        $this->_saveData("unified", $package, $prettyPrint);
    }

    
    //CACHING !
    public function __call($method, $arguments) {

        //caching...
        if(!array_key_exists($method, $this->_cache)) {
            $method = "_" . $method;
            $this->_cache[$method] = $this->$method();
        }

        return $this->_cache[$method];
    }

    ///// private
    private $_iTargetPath;
    private $_lib;
    private $_username;
    private $_cache = array();

    private static $_outputTargets = array(
        "albgl", "albgc",
        "arbgl", "arbgc",
        "glul",
        "abal",
        "slug"
    );

    private function _generateData() {
        foreach(self::$_outputTargets as $target) {
            $this->getDataPart($target);
        }
    }

    private function _saveData($target, &$data, $pp) {
        $data = json_encode($data, $pp ? JSON_PRETTY_PRINT : null);
        file_put_contents($this->_iTargetPath . $target . ".json", $data);
    }

    //////////////////////
    // databits helpers //
    //////////////////////

    private function _getMixedTrackNoFactory(&$currentVal) {
        return $currentVal['Disc Number'] . "." . $currentVal['Track Number'];
    }

    private function _getTrackNameFactory(&$currentVal) {
        return $currentVal["Name"];
    }

    private function _getYearFactory(&$currentVal) {
        return $currentVal['Year'];
    }

    private function _getAlbumArtistFactory(&$currentVal) {
        return $currentVal['Album Artist'];
    }

    private function _getAlbumFactory(&$currentVal) {
        return $currentVal['Album'];
    }

    private function _getAlbumIdFactory(&$currentVal) {
        return $currentVal['Album'] . "_" 
        . $currentVal['Album Artist'] . "_" 
        . $currentVal['Year'];
    }

    private function _getDateAddedFactory(&$currentVal) {
        return $currentVal["Date Added"];
    }

    private function _getGenreFactory(&$currentVal) {
        return ucwords($currentVal["Genre"]);
    }

    ////////////////////////////
    // dataprocessing helpers //
    ////////////////////////////

    private function _GenreStats(&$lib, $idFactory = null) {
        $data = array_reduce($lib, function($total, $currentVal) use ($idFactory) {
            $genre = $this->_getGenreFactory($currentVal);
            $id = $idFactory($currentVal);
            
            if(!array_key_exists($genre, $total)) {
                $total[$genre] = array();
            }
            
            array_push($total[$genre], $id);
    
            return $total;
    
        }, array());

        $data = array_map("array_unique", $data);
        $data = array_map("array_values", $data);

        return $data;
    } 
    
    private function _StatsCount($data) {
        $data = array_map("count", $data);
        arsort($data);
        return $data;
    }

    ///////////
    // funcs //
    ///////////

    /*albumsByGenreCount*/
    private function _albgc() {
        return $this->_StatsCount(
            $this->albgl()
        );
    } 

    /*artistsByGenreCount*/
    private function _arbgc() {
        return $this->_StatsCount(
            $this->arbgl()
        );
    } 

    /*albumsByGenreList*/
    private function _albgl() {
        return $this->_GenreStats($this->_lib, function($currentVal) {
            return $this->_getAlbumIdFactory($currentVal);
        });
    } 

    /*artistsByGenreList*/
    private function _arbgl() {
        return $this->_GenreStats($this->_lib, function($currentVal) {
            return $this->_getAlbumArtistFactory($currentVal);
        });
    } 

    /*albumsByArtistsList*/
    private function _abal() {
        $data = array_reduce($this->_lib, function($total, $currentVal) {

            //prepare
            $artist = $this->_getAlbumArtistFactory($currentVal);
            $album = $this->_getAlbumFactory($currentVal);
            $genre = $this->_getGenreFactory($currentVal);
            $year = $this->_getYearFactory($currentVal);
            $trackName = $this->_getTrackNameFactory($currentVal);
            $dateAdded = $this->_getDateAddedFactory($currentVal);

            //if first occurence artist
            if(!array_key_exists($artist, $total)) {
                $total[$artist] = array(
                    "Genres" => [],
                    "Albums" => []
                );
            }

            //add genre
            array_push($total[$artist]["Genres"], $genre);

            //if first occurence album
            if (!array_key_exists($album, $total[$artist]["Albums"])) {
                $total[$artist]["Albums"][$album] = array(
                    "Year" => $year,
                    "Genre" => $genre,
                    "Tracks" => array(),
                    "DateAdded" => $dateAdded
                );
            }
            //add track
            $total[$artist]["Albums"][$album]["Tracks"][$this->_getMixedTrackNoFactory($currentVal)] = $trackName;
                    
            return $total;

        }, array());
        
        //uniques GENRES! 
        foreach($data as $key => $value)
        {
            $data[$key]['Genres'] = array_values(
                array_unique($data[$key]['Genres'])
            );
        }

        return $data;
    }


    //albumsByIdList
    private function _glul() {
        return array_reduce($this->_lib, function($total, $currentVal) {
            
            $da = $this->_getDateAddedFactory($currentVal);
            $albumId = $this->_getAlbumIdFactory($currentVal);

            if(!array_key_exists($albumId, $total)) {
                $total[$albumId] = array(
                    "Album" => $this->_getAlbumFactory($currentVal),
                    "Artist" => $this->_getAlbumArtistFactory($currentVal),
                    "Year" => $this->_getYearFactory($currentVal),
                    "DateAdded" => $da,
                    "Genre" => $this->_getGenreFactory($currentVal)
                );
            } else {
                $old_da = $total[$albumId]["DateAdded"];
                if ($da > $old_da) $total[$albumId]['DateAdded'] = $da;
            }

            return $total;

        }, array());
    }

    private function _slug() {
        $slgfr = new Slugifier;
        $data = $this->abal();
        $total = array();
        foreach($data as $key => $value) {
            $total[$key] = $slgfr($key);
        }

        //sort by string length then alpha
        uksort($total, function($a, $b) {
            return strlen($a) - strlen($b) ?: strcmp($a, $b);
        });

        array_walk($total, function(&$a, $b) { $a = array($a, $b); });

        return array_values($total);
    }
}