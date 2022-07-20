<?php

add_shortcode('yearWinners', 'lyra_year_winners_shortcode');
add_shortcode('RegattaInfo', 'lyra_year_Reggatta_shortcode');
add_shortcode('yearNonBoatWinners', 'lyra_year_winners_nonBoat_shortcode');

function getYear()
{
   // look in querystring for Year, note case sensitive
   $queryParamater = $_GET['regattYear'] ?? -999;

   // echo "paramater:";
   // echo $queryParamater;

   if (empty($queryParamater) || !is_numeric($queryParamater)) {
      $queryParamater = 2020; // Lost trophy....
   }

   if ($queryParamater < 1850 || $queryParamater > 2100) {
      $queryParamater = 2020; // Lost trophy....
   }

   return $queryParamater;
}

function lyra_year_winners_shortcode($atts = [], $content = null, $tag = '')
{
   global $wpdb;

   $sql = "SELECT T.TrophyNameShort, W.AwardedFor, B.BoatName, Skipper, BoatType, HomeClub, B.BoatID, W.TrophyID " .
      "FROM " . $wpdb->prefix . "lyra_trophywinners W INNER JOIN " . $wpdb->prefix . "lyra_trophies T ON W.TrophyID = T.TrophyID " .
      "INNER JOIN " . $wpdb->prefix . "lyra_boat B ON W.BoatID = B.BoatID " .
      "where 	W.BoatID <> 143 AND W.regattaYear= " . getYear() .
      " ORDER BY T.ReggattaDisplayOrder, T.TrophyNameShort;";

   $posts = $wpdb->get_results($sql);

   ob_start();

   $output = "<table class='trophyListing'><thead><tr>
         <td>Trophy</td>
         <td>Awarded For</td>
         <td>Boat</td>
         <td>Skipper</td>
         <td>Boat Type</td>
         <td>Home Club</td>
         </tr></thead><tbody>";
   foreach ($posts as $post) {
      $output .=
         "<tr><td>" . buildTrophyLink($post->TrophyID, $post->TrophyNameShort) . "</td>
         <td>" . $post->AwardedFor . "</td>
         <td>" . buildBoatLink($post->BoatID, $post->BoatName) . "</td>
         <td>" . $post->Skipper . "</td>
         <td>" . $post->BoatType . "</td>
         <td>" . $post->HomeClub . "</td></tr>";
   }
   $output .= "</tbody> </table>";

   echo $output;

   return ob_get_clean();
}

function lyra_year_Reggatta_shortcode($atts = [], $content = null, $tag = '')
{

   global $wpdb;

   $sql = "SELECT HostClubs,  Description, LinksToArchive FROM " .
      $wpdb->prefix . "lyra_regattas WHERE regattaYear =" . getYear() . ";";

   $post = $wpdb->get_row($sql);

   ob_start();



   $output = "<div class='trophyTitle'>" .
      $post->HostClubs .  "</div>" .
      "<div class='trophyText'>" .
      $post->Description .
      "</div>";

   echo $output;

   return ob_get_clean();
}


function lyra_year_winners_nonBoat_shortcode($atts = [], $content = null, $tag = '')
{
   global $wpdb;


   $sql = "SELECT T.TrophyID, T.TrophyNameShort, Winner, AwardedFor " .
      "FROM  " . $wpdb->prefix . "lyra_trophywinners_notboats W " .
      "INNER JOIN  " . $wpdb->prefix . "lyra_trophies T ON W.TrophyID = T.TrophyID " .
      "where Winner <> 'Not awarded' AND regattaYear = " . getYear() .
      " ORDER BY  ReggattaDisplayOrder, T.TrophyNameShort";

   $posts = $wpdb->get_results($sql);

   ob_start();

   if (count($posts) > 0) {
         $output = "<table class='trophyListing'><thead><tr>
      <td>Trophy</td>
      <td>Winner For</td>
      <td>Awarded</td>
      </tr></thead><tbody>";
         foreach ($posts as $post) {
            $output .=
               "<tr><td>" . buildTrophyLink($post->TrophyID, $post->TrophyNameShort) . "</td>" .
               "<td>" . $post->Winner . "</td> " .
               "<td>" . $post->AwardedFor . "</td></tr>";
         }
      $output .= "</tbody> </table>";

      echo $output;
   }
  

   return ob_get_clean();
}
