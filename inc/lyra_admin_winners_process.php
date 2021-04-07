<?php
class Winners
{

    public $regattaYear;
    public $TrophyID;
    public $AwardedFor;
    public $BoatID;


    public static function process()
    {
        // A default response holder, which will have data for sending back to our js file
        $response = array(
            'error' => false,
        );

        $winnerData = new Winners();

        $activity = $_POST['activity']; // front end action request. CRUD
        $winnerData->BoatID = $_POST['BoatID'] ?? -999;
        $winnerData->regattaYear = $_POST['regattaYear'] ?? -999;
        $winnerData->TrophyID = $_POST['TrophyID'] ?? -99;
        $winnerData->OrigBoatID = $_POST['OrigBoatID'] ?? -999;
        $winnerData->AwardedFor = $_POST['AwardedFor'] ?? '';

       //   error_log('FormData' . $winnerData);

        switch ($activity) {
            case 'GetTropies':
                $returnMsg = $winnerData->TrophiesListing();
                break;

            case 'GetBoats':
                $returnMsg = $winnerData->BoatsListing();
                break;

            case 'GetWinners':
                $returnMsg = $winnerData->Winners($winnerData);
                break;
            case 'Save':
                // Update existing record;
                $returnMsg = $winnerData->update($winnerData);
                break;

            case 'Delete':
                // get the Winner details and any trophies the won.
                $returnMsg = $winnerData->delete($winnerData);
                break;
            default:
                # code...
                break;
        }

        wp_send_json_success($returnMsg);

        // Always die in functions echoing Ajax content
        // wp_die();
    }

    // handle the individual work request from the front end.

    //********************************************************************************** */
    // Get list of tophies for display.
    function TrophiesListing()
    {
        global $wpdb;

        $trophies = $wpdb->get_results(
            "SELECT    
    TrophyID, TrophyNameShort AS DisplayName FROM " .  $wpdb->prefix . "lyra_trophies
        WHERE TrophyColumnLabel ='Boat'
        ORDER BY TrophyNameShort"
        );

        // convert into JSON
        $data = json_encode($trophies);

        $userMsg = 'Loaded trophies';

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem getting trophies from database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'trophiesListing' => $data,
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }
    //********************************************************************************** */
    function BoatsListing()
    {
        global $wpdb;

        $dbdata = $wpdb->get_results(
            "SELECT    
        BoatID, CONCAT_WS(', ', BoatName, Skipper) AS DisplayName FROM " .  $wpdb->prefix . "lyra_boat
            ORDER BY BoatName, Skipper"
        );

        // convert into JSON
        $data = json_encode($dbdata);

        $userMsg = 'Loaded boat and skipper names.';

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem getting boat and skipper names from database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'boatsListing' => $data,
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }


    function Winners(Winners $postData = null)
    {
        global $wpdb;

        $dbdata = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT BoatID, AwardedFor   
            FROM " .  $wpdb->prefix . "lyra_trophywinners 
            WHERE regattaYear =%d AND TrophyID = %d",
                $postData->regattaYear,
                $postData->TrophyID
            )
        );

        // convert into JSON
        $data = json_encode($dbdata);

        $userMsg = 'Loaded winner\'s boat and skipper names.';

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem getting boat and skipper names from database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'winners' => $data,
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }

 

    //********************************************************************************** */
    // update winners, check if boat id = orig boat id, if no then need to delete orig and add new.
    // check if record already exist, if yes then update, if no then insert.

    function update($winnerData)
    {
        global $wpdb;

        // if origboatid <> -999 then clean records. 
        $sql = '';
            $sql = 
            $wpdb->prepare(
                " DELETE FROM " .  $wpdb->prefix . "lyra_trophywinners 
                WHERE regattaYear=%d AND TrophyID=%d AND (BoatID=%d OR BoatID=%d);",
                $winnerData->regattaYear,
                $winnerData->TrophyID,
                $winnerData->BoatID,
                $winnerData->OrigBoatID
            );
                $wpdb->query($sql );
 
            $wpdb->query(
                $wpdb->prepare(
                    " INSERT INTO " .  $wpdb->prefix . "lyra_trophywinners 
                    (
                        regattaYear, TrophyID, BoatID, AwardedFor
                    )
                VALUES (%d, %d, %d, %s);",
                    $winnerData->regattaYear,
                    $winnerData->TrophyID,
                    $winnerData->BoatID,
                    $winnerData->AwardedFor
                )
            );

           $BoatName = $wpdb->get_var(
            "SELECT  BoatName 
            FROM " .  $wpdb->prefix . "lyra_boat
            WHERE BoatID =" . $winnerData->BoatID
        );
         
        $userMsg =   "Added <i>" . $BoatName . "</i> to the winners list";

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem adding boat to database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }


  

    //********************************************************************************** */
    // Boat Detials
    function delete($winnerData)
    {
         global $wpdb;

         $BoatName = $wpdb->get_var(
            "SELECT  BoatName 
            FROM " .  $wpdb->prefix . "lyra_boat
            WHERE BoatID =" . $winnerData->BoatID
        );

         $table = $wpdb->prefix . "lyra_trophywinners";
         $boatsDelete = $wpdb->delete($table, array(
             'BoatID' => $winnerData->BoatID,
            'regattaYear' => $winnerData->regattaYear,
             'TrophyID' => $winnerData->TrophyID
            ));


        if ($wpdb->last_error !== '') {
            $wpdb->print_error();
            $userMsg = "Sorry, had a problem getting data from the server for this boat.";
        } else {
            // 
            if ($boatsDelete = 0) {
                $userMsg = 'Did not delete a boat. Please try again.';
             } else {
                $userMsg = 'Deleted ' . $BoatName . ' from list of winners.';
            }
        }


        $returnMsg = array(
            'UserMessage' =>  $userMsg
        );
        return $returnMsg;
    }
}
