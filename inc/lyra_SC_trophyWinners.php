<?php

add_shortcode('trophyWinners', 'lyra_trophy_winners_shortcode');
add_shortcode('trophyDescription', 'lyra_trophy_description_shortcode');
add_shortcode('trophyTitle', 'lyra_trophy_title_shortcode');
add_shortcode('trophyList', 'lyra_trophy_list_shortcode');
add_shortcode('trophyWinners_nonBoat', 'lyra_trophy_winners_nonBoat_shortcode');
function getTrophyID()
{
   // look in querystring for trophyId, note case sensitive
   $queryParamater = $_GET['trophyId'] ?? 0;

   // echo "paramater:";
   // echo $queryParamater;

   if (empty($queryParamater) || !is_numeric($queryParamater)) {
      $queryParamater = 0; // Lost trophy....
   }

   if ($queryParamater < 0 || $queryParamater > 100) {
      $queryParamater = 0; // Lost trophy....
   }

   return $queryParamater;
}

function lyra_trophy_winners_shortcode($atts = [], $content = null, $tag = '')
{
   global $wpdb;

   $sql = "SELECT regattaYear, BoatName, BoatID FROM " .
      "lyra_winners WHERE TrophyID =" . getTrophyID() . " AND BoatID <> 143".
      " ORDER BY regattaYear DESC";

    $posts = $wpdb->get_results($sql);

   ob_start();

   $output = "<div class='trophyList'>";
   foreach ($posts as $post) {
      $output .=
         "<div class='yearRow'><div class='year'>" .buildYearLink($post->regattaYear) . "</div>". 
         "<div class='boat'>". buildBoatLink($post->BoatID, $post->BoatName) ."</div></div>";
   }
   $output .= "</div>";
   echo $output;

   return ob_get_clean();
}

function lyra_trophy_description_shortcode($atts = [], $content = null, $tag = '')
{

   global $wpdb;

   $sql = "SELECT TrophyNameDetails, RaceDetails, LongDescription, PictureLink FROM " .
      $wpdb->prefix . "lyra_trophies WHERE TrophyID ='" . getTrophyID() . "'"; 

   $post = $wpdb->get_row($sql);

   ob_start();



   $output = "<div class='trophyDescription'>" .
      "<a href='http://lyrawaters.org/WP1/" . $post->PictureLink . "'>" .
      "<img class='trophyPic' src='http://lyrawaters.org/WP1/" . $post->PictureLink . "' alt='' /></a>" .
      "</div>".
      "<div class='trophyText'>" .
      $post->LongDescription .
      "</div>";

   echo $output;

   return ob_get_clean();
}


function lyra_trophy_winners_nonBoat_shortcode($atts = [], $content = null, $tag = '')
{
   global $wpdb;

   $sql = "SELECT regattaYear, Winner FROM " .
   $wpdb->prefix . "lyra_trophywinners_notboats WHERE TrophyID ='" . getTrophyID() .  "' AND Winner <> 'Not awarded'" .
      " ORDER BY regattaYear DESC";

   $posts = $wpdb->get_results($sql);

   ob_start();

   $output = "<div class='boatsTrophiesList'>";
   foreach ($posts as $post) {
      $output .=
         "<div class='yearRow'><div class='year'>" .buildYearLink($post->regattaYear) . "</div>". 
         "<div class='boat'>".$post->Winner."</div></div>";
   }
   $output .= "</div>";
   echo $output;

   return ob_get_clean();
}


function lyra_trophy_title_shortcode($atts = [], $content = null, $tag = '')
{


   global $wpdb;

   $sql = "SELECT TrophyNameShort FROM " .
      $wpdb->prefix . "lyra_trophies WHERE TrophyID ='" . getTrophyID() . "'";

   $post = $wpdb->get_row($sql);

   ob_start();



   $output = "<div class='trophyTitle'>" . $post->TrophyNameShort.
      "</div>";

   echo $output;

   return ob_get_clean();
}


function lyra_trophy_list_shortcode($atts = [], $content = null, $tag = '')
{
   // list out all of the trophies.

     global $wpdb;

   $sql = "SELECT TrophyID, TrophyNameShort, DeedOfGift, YearFirstAwarded, RaceDetails,TrophyColumnLabel  " .
         "FROM " . $wpdb->prefix . "lyra_trophies WHERE TrophyColumnLabel <> 'Flag' " .
         " AND TrophyID > 0 " .
         "ORDER BY TrophyPageDisplayOrder, TrophyNameShort ASC;";

      $posts = $wpdb->get_results($sql);

      ob_start();
   
      $output = "<table class='trophyListing'><thead><tr>
            <td>Trophy</td>
            <td >First Awarded</td>
            <td>Deed of Gift</td>
            <td>Race Details</td>
            </tr></thead><tbody>";
      foreach ($posts as $post) {
            if ($post->TrophyColumnLabel == 'Person') { 
               $page = "trophy-winners-nonboat";
            } else {
               $page = "trophydetails";
            }
            

         $output .=
         "<tr><td><a href='../". $page . "/?trophyId=" .$post->TrophyID . "'>" .$post->TrophyNameShort . "</a></td>
         <td>" .$post->YearFirstAwarded . "</td>
         <td>" .$post->DeedOfGift . "</td>
         <td>" .$post->RaceDetails . "</td></tr>";

      }
      $output .= "</tbody> </table>";
      echo $output;
   
      return ob_get_clean();
}

//  <div class="trophyDescription">
//  <a href="http://lyrawaters.org/WP1/wp-content/uploads/2017/11/Charles-Freeman-Cup.jpg">
//  <img class="trophyPic" src="http://lyrawaters.org/WP1/wp-content/uploads/2017/11/Charles-Freeman-Cup.jpg" alt="" /></a>After World War I, short-course racing predominated at LYRA regattas. However, distance racing was revived in 1921 by the establishment of a new long distance race, and donation by Charles Freeman of a trophy for that race. The first winner of this new cup was Aemilius Jarvis, with the schooner Haswell, on a stormy course from Hamilton to Kingston. The original cup was destroyed in a fire in 1931, but a replica was procured immediately from England.
 
//  The cup was subsequently awarded to the overall IMS winner in the Freeman Cup Race, and it is now awarded to the overall winner in the IRC fleet.
//  </div>