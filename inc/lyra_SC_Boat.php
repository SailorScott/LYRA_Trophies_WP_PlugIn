<?php
// [BoatName boatID=92]
// [BoatDetails boatID=92]
// [BoatTrophies boatID=92]
// [BoatSameName boatID=92]
add_shortcode('BoatDetails', 'lyra_boat_detail_shortcode');

add_shortcode('BoatsListing', 'lyra_boat_listing_shortcode');


function getBoatID()
{
   // look in querystring for trophyId, note case sensitive
   $queryParamater = $_GET['BoatId'];

   // echo "paramater:";
   // echo $queryParamater;

   if (empty($queryParamater) || !is_numeric($queryParamater)) {
      $queryParamater = 0; // Lost boat....
   }

   if ($queryParamater < 1 || $queryParamater > 1000) {
      $queryParamater = 0; // Lost trophy....
   }

   return $queryParamater;
}



function lyra_boat_detail_shortcode($atts = [], $content = null, $tag = '')
{
// Function handles the calling a particular boat by boat ID or by boat name and looking for other boat ids with same name.

    global $wpdb;
    
    ob_start();

    if (isset($_GET['BoatId'])) {
            $boatid = getBoatID();
            $html =  outputOneBoatDetail($boatid);
            echo $html;
    }
    elseif (isset($_GET['BoatName'])) {
        $stmt = $wpdb->prepare(
                "SELECT boatid FROM lyra_boatname2boatids where boatname = %s ORDER BY FirstWin DESC"
                , $_GET['BoatName']
            );
        $posts = $wpdb->get_results($stmt);
        foreach ($posts as $post) {
            $html =  outputOneBoatDetail($post->boatid);
            echo $html;
        }
  }


     return ob_get_clean();


}

function outputOneBoatDetail($boatid)
{
    global $wpdb;


    $sql = "SELECT BoatName, Skipper, BoatType, HomeClub FROM " .
       $wpdb->prefix . "lyra_boat WHERE BoatID ='" . $boatid . "'";
 
    $post = $wpdb->get_row($sql);
 
   // ob_start();
 
 
    $output = "<div class='boatNameTitle'>" . $post->BoatName."</div>";
 
     $output = $output. "<div class='boatDetails'>Skipper: " . $post->Skipper."</div>";
     $output = $output. "<div class='boatDetails'>Boat: " . $post->BoatType."</div>";
     $output = $output. "<div class='boatDetails'>Home Club: " . $post->HomeClub."</div>";
 
  //  echo $output;
 
    $sql = "SELECT regattaYear, TrophyNameShort, AwardedFor, TrophyID FROM " .
       "lyra_winners WHERE BoatID ='" . $boatid .
       "' ORDER BY regattaYear DESC";
 
    $posts = $wpdb->get_results($sql);
 

    $output .= "<div class='boatsTrophiesList'>";
    foreach ($posts as $post) {
       $output .=
          "<div class='yearRow'><div class='year'>" . buildYearLink($post->regattaYear) 
          . "</div><div class='boat'>" .
          buildTrophyLink($post->TrophyID, $post->TrophyNameShort );
          
          if (!empty($post->AwardedFor)) {
            $output .= "</div><div class='AwardedFor'>Awarded for: " . $post->AwardedFor;
          }
         $output .= "</div></div>";
    }
    $output .= "</div>";
  //  echo $output;
 
 
    return $output;

}

function lyra_boat_listing_shortcode($atts = [], $content = null, $tag = '')
{

   global $wpdb;

   $boatid = getBoatID();

   $sql = "SELECT DISTINCT BoatName FROM " .
      $wpdb->prefix . "lyra_boat ORDER BY BoatName;";

    $posts = $wpdb->get_results($sql);

   ob_start();

   $output = "<div class='boatsList'>";
   foreach ($posts as $post) {
      $output .=
         "<div class='yearRow'>".
         "<div class='year'><a href='../boat-details/?BoatName=".  urlencode($post->BoatName)."'>".$post->BoatName."</a></div>".
               "</div>";
   }
   $output .= "</div>";
   echo $output;


   return ob_get_clean();
}




