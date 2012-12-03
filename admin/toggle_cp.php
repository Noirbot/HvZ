<?
require("../scripts/lib.php");
$faction = verify($gt_name);
if( $faction == 'admin' ){

    if(isset($_GET['owner']) and isset($_GET['point'])){
        $owner = $_GET['owner'];
        $point = $_GET['point'];

        mysql_query("UPDATE capture SET owner = $owner WHERE id=$point");
    }
}
?>