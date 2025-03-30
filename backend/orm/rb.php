<?php
require 'vendor/autoload.php';

class ORM {
    public static function init() {
        R::setup('mysql:host=localhost;dbname=my_unique_db', 'root', '');
        R::useFeatureSet('novice/latest');
    }
}
?>