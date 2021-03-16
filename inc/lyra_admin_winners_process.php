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
        $winnerData->BoatID = $_POST['BoatID'] ?? -99;
        $winnerData->regattaYear = $_POST['regattaYear'] ?? -99;
        $winnerData->TrophyID = $_POST['TrophyID'] ?? -99;
        $winnerData->AwardedFor = $_POST['AwardedFor'] ?? '';

      //  error_log('FormData' . $winnerData);

        switch ($activity) {
            case 'GetTropies':
                $returnMsg = $winnerData->TrophiesListing();
                break;
 
            case 'Save':
                if ($winnerData->BoatID === -99) {
                    // insert new Winner
                    $returnMsg = create($winnerData);
                } else {
                    // Update existing record;
                    $returnMsg = update($winnerData);
                }
                break;
 
            case 'Delete':
                // get the Winner details and any trophies the won.
                $returnMsg = delete($winnerData->BoatID);
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
    // create boat
    function create($winnerData)
    {
        global $wpdb;

        // $wpdb->query(
        //     $wpdb->prepare(
        //         "INSERT INTO " .  $wpdb->prefix . "lyra_trophywinners SET 
        //     regattaYear=%s, 
        //     TrophyID=%s, 
        //     BoatID=%s
        //     AwardedFor=%s
        //     ",
        //         $winnerData->regattaYear,
        //         $winnerData->TrophyID,
        //         $winnerData->BoatID,
        //         $winnerData->AwardedFor
        //     )
        // );

        // $userMsg = 'Successfully saved ' . $winnerData->regattaYear;

        // if ($wpdb->last_error !== '') :
        //     $wpdb->print_error();
            $userMsg = "Error: Problem adding boat to database. Please see the error log for more details.";
        // endif;

        $returnMsg = array(
            // 'newBoatID' => $wpdb->insert_id,
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }

    //********************************************************************************** */
    // update boat
    function update($winnerData)
    {
        // global $wpdb;

        // $wpdb->query(
        //     $wpdb->prepare(
        //         "UPDATE " .  $wpdb->prefix . "lyra_trophywinners SET 
        //     regattaYear=%s, 
        //     TrophyID=%s, 
        //     AwardedFor=%s, 
        //     DELETE=%s 
        //     WHERE BoatID = %d",
        //         $winnerData->regattaYear,
        //         $winnerData->TrophyID,
        //         $winnerData->AwardedFor,
        //         $winnerData->DELETE,
        //         $winnerData->BoatID
        //     )
        // );

        // $userMsg = 'Successfully saved ' . $winnerData->regattaYear;

        // if ($wpdb->last_error !== '') :
        //     $wpdb->print_error();
            $userMsg = "Error: Problem adding boat to database. Please see the error log for more details.";
        // endif;

        $returnMsg = array(
   //         'newBoatID' => $winnerData->BoatID,
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }

    //********************************************************************************** */
    // Find boat
    function findWinner($winnerData)
    {
        // global $wpdb;

        // $boats = $wpdb->get_results(
        //     "SELECT    
        // BoatID, 
        // CONCAT_WS(' - ', regattaYear, IF (LENGTH(TRIM(TrophyID)) > 1, TrophyID, NULL)) AS DisplayName            FROM " .  $wpdb->prefix . "lyra_trophywinners
        //     WHERE regattaYear LIKE '%"
        //         . $wpdb->esc_like($winnerData->FilterBoatsList) . "%' 
        //         OR TrophyID LIKE '%" . $wpdb->esc_like($winnerData->FilterBoatsList) . "%'
        //     ORDER BY regattaYear
        //     LIMIT 400"
        // );

        // // convert into JSON
        // $data = json_encode($boats);

        // $rowCount = $wpdb->get_var(
        //     "SELECT  COUNT(*) 
        //     FROM " .  $wpdb->prefix . "lyra_trophywinners
        //     WHERE regattaYear LIKE '%"
        //         . $wpdb->esc_like($winnerData->FilterBoatsList) . "%' 
        //         OR TrophyID LIKE '%" . $wpdb->esc_like($winnerData->FilterBoatsList) . "%'"
        // );
        // if ($wpdb->last_error !== '') {
        //     $wpdb->print_error();
        //     $userMsg = "Sorry, had a problem getting data from the server with the filter function.";
        // } elseif ($rowCount > 400) {
        //     $userMsg = 'We found more then 400 boats that match "' . $winnerData->FilterBoatsList . '". We can only show the first 400, please refine your search.';
        // } else {
        //     $userMsg = 'Found ' . $rowCount . ' boats that match "' . $winnerData->FilterBoatsList . '".';
        // }

        $returnMsg = array(
            // 'Boats' => $data,
            'UserMessage' =>  $userMsg
        );
        return $returnMsg;
    }
    //********************************************************************************** */
    // Boat Detials
    function winnerDetails(int $boatID = null)
    {
        global $wpdb;

        // $boat = $wpdb->get_row(
        //     $wpdb->prepare(
        //         "SELECT    
        //     BoatID, 
        //     regattaYear, 
        //     TrophyID, 
        //     AwardedFor, 
        //     DELETE 
        //     FROM " .  $wpdb->prefix . "lyra_trophywinners
        //     WHERE BoatID = %d",
        //         $boatID
        //     )
        // );

        // // convert into JSON
        // $winnerData = json_encode($boat);

        // $trophies = $wpdb->get_results(
        //     $wpdb->prepare(
        //         "SELECT trophyID, 
        //     CONCAT(regattaYear, ' - ', TrophyNameShort) AS DisplayName
        //     FROM `lyra_winners`
        //     WHERE BoatID =  %d
        //     ORDER BY regattaYear, TrophyNameShort",
        //         $boatID
        //     )
        // );

        // if ($wpdb->last_error !== '') {
        //     $wpdb->print_error();
        //     $userMsg = "Sorry, had a problem getting data from the server for this boat.";
        // } else {
            $userMsg = 'Found a boat!';
        // }


        $returnMsg = array(
            // 'BoatDetail' => $winnerData,
            // 'Trophies' => $trophies,
            // 'UserMessage' =>  $userMsg
        );
        return $returnMsg;
    }

    //********************************************************************************** */
    // Boat Detials
    function delete(int $boatID = null)
    {
        // global $wpdb;

        // $boatsDeleted = 0;
        // $winningsDeleted = 0;

        // $table = $wpdb->prefix . "lyra_trophywinners";
        // $boatsDelete = $wpdb->delete($table, array('BoatID' => $boatID));


        // $table = $wpdb->prefix . "lyra_trophywinners";
        // $winningsDeleted = $wpdb->delete($table, array('BoatID' => $boatID));

        // if ($wpdb->last_error !== '') {
        //     $wpdb->print_error();
        //     $userMsg = "Sorry, had a problem getting data from the server for this boat.";
        // } else {
        //     // 
        //     if ($boatsDelete = 0) {
        //         $userMsg = 'Did not delete a boat. Please try again.';
        //     } elseif ($winningsDeleted > 0) {
        //         $userMsg = 'Deleted the boat and ' . $winningsDeleted . ' winnings.';
        //     } else {
                $userMsg = 'Deleted the boat';
        //     }
        // }


        $returnMsg = array(
            'UserMessage' =>  $userMsg
        );
        return $returnMsg;
    }
}
?>