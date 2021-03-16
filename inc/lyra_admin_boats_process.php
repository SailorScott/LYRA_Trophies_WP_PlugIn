<?php
class Boat
{

    public $FilterBoatsList;
    public $BoatName;
    public $Skipper;
    public $BoatType;
    public $HomeClub;
    public $BoatID;
}

function processBoat()
{
    // A default response holder, which will have data for sending back to our js file
    $response = array(
        'error' => false,
    );

    $boatData = new Boat();

    $activity = $_POST['activity']; // front end action request. CRUD
    $boatData->FilterBoatsList = $_POST['FilterBoatsList'] ?? '';
    $boatData->BoatID = $_POST['BoatID'] ?? -99;
    $boatData->BoatName = $_POST['BoatName'] ?? '';
    $boatData->Skipper = $_POST['Skipper'] ?? '';
    $boatData->BoatType = $_POST['BoatType'] ?? '';
    $boatData->HomeClub = $_POST['HomeClub'] ?? '';

    error_log('FormData' . $boatData->BoatName);

    switch ($activity) {
        case 'Save':
            if ($boatData->BoatID === -99) {
                // insert new boat
                $returnMsg = create($boatData);
            } else {
                // Update existing record;
                $returnMsg = update($boatData);
            }
            break;
        case 'Find':
            // pull out the boats that match the find statement;
            $returnMsg = findBoat($boatData);
            break;
        case 'BoatDetails':
            // get the boat details and any trophies the won.
            $returnMsg = boatDetails($boatData->BoatID);
            break;
        case 'DeleteBoat':
                // get the boat details and any trophies the won.
                $returnMsg = deleteBoat($boatData->BoatID);
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
// create boat
function create($boatData)
{
    global $wpdb;

    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO " .  $wpdb->prefix . "lyra_boat SET 
            BoatName=%s, 
            Skipper=%s, 
            BoatType=%s, 
            HomeClub=%s 
            ",
            $boatData->BoatName,
            $boatData->Skipper,
            $boatData->BoatType,
            $boatData->HomeClub
        )
    );

    $userMsg = 'Successfully saved ' . $boatData->BoatName;

    if ($wpdb->last_error !== '') :
        $wpdb->print_error();
        $userMsg = "Error: Problem adding boat to database. Please see the error log for more details.";
    endif;

    $returnMsg = array(
        'newBoatID' => $wpdb->insert_id,
        'UserMessage' =>  $userMsg
    );


    return $returnMsg;
}

//********************************************************************************** */
// update boat
function update($boatData)
{
    global $wpdb;

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE " .  $wpdb->prefix . "lyra_boat SET 
            BoatName=%s, 
            Skipper=%s, 
            BoatType=%s, 
            HomeClub=%s 
            WHERE BoatID = %d",
            $boatData->BoatName,
            $boatData->Skipper,
            $boatData->BoatType,
            $boatData->HomeClub,
            $boatData->BoatID
        )
    );

    $userMsg = 'Successfully saved ' . $boatData->BoatName;

    if ($wpdb->last_error !== '') :
        $wpdb->print_error();
        $userMsg = "Error: Problem adding boat to database. Please see the error log for more details.";
    endif;

    $returnMsg = array(
        'newBoatID' => $boatData->BoatID,
        'UserMessage' =>  $userMsg
    );


    return $returnMsg;
}

//********************************************************************************** */
// Find boat
function findBoat($boatData)
{
    global $wpdb;

    $boats = $wpdb->get_results(
             "SELECT    
        BoatID, 
        CONCAT_WS(' - ', BoatName, IF (LENGTH(TRIM(Skipper)) > 1, Skipper, NULL)) AS DisplayName            FROM " .  $wpdb->prefix . "lyra_boat
            WHERE BoatName LIKE '%"
                . $wpdb->esc_like($boatData->FilterBoatsList) . "%' 
                OR Skipper LIKE '%" . $wpdb->esc_like($boatData->FilterBoatsList) . "%'
            ORDER BY BoatName
            LIMIT 400"
    );

    // convert into JSON
    $data = json_encode($boats);

    $rowCount = $wpdb->get_var(
            "SELECT  COUNT(*) 
            FROM " .  $wpdb->prefix . "lyra_boat
            WHERE BoatName LIKE '%"
                . $wpdb->esc_like($boatData->FilterBoatsList) . "%' 
                OR Skipper LIKE '%" . $wpdb->esc_like($boatData->FilterBoatsList) . "%'"
    );
    if ($wpdb->last_error !== '') {
        $wpdb->print_error();
        $userMsg = "Sorry, had a problem getting data from the server with the filter function.";
    } elseif ($rowCount > 400) {
        $userMsg = 'We found more then 400 boats that match "' . $boatData->FilterBoatsList . '". We can only show the first 400, please refine your search.';
    } else {
        $userMsg = 'Found ' . $rowCount . ' boats that match "' . $boatData->FilterBoatsList . '".';
    }

    $returnMsg = array(
        'Boats' => $data,
        'UserMessage' =>  $userMsg
    );
    return $returnMsg;
}
//********************************************************************************** */
// Boat Detials
function boatDetails(int $boatID = null)
{
    global $wpdb;

    $boat = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT    
            BoatID, 
            BoatName, 
            Skipper, 
            BoatType, 
            HomeClub 
            FROM " .  $wpdb->prefix . "lyra_boat
            WHERE BoatID = %d",
            $boatID
        )
    );

    // convert into JSON
    $boatdata = json_encode($boat);

    $trophies = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT trophyID, 
            CONCAT(regattaYear, ' - ', TrophyNameShort) AS DisplayName
            FROM `lyra_winners`
            WHERE BoatID =  %d
            ORDER BY regattaYear, TrophyNameShort",
            $boatID
        )
    );

    if ($wpdb->last_error !== '') {
        $wpdb->print_error();
        $userMsg = "Sorry, had a problem getting data from the server for this boat.";
    } else {
        $userMsg = 'Found a boat!';
    }


    $returnMsg = array(
        'BoatDetail' => $boatdata,
        'Trophies' => $trophies,
        'UserMessage' =>  $userMsg
    );
    return $returnMsg;
}

//********************************************************************************** */
// Boat Detials
function deleteBoat(int $boatID = null)
{
    global $wpdb;

    $boatsDeleted = 0;
    $winningsDeleted = 0;

    $table = $wpdb->prefix . "lyra_boat";
    $boatsDelete = $wpdb->delete( $table, array( 'BoatID' => $boatID ));


    $table = $wpdb->prefix . "lyra_trophywinners";
    $winningsDeleted = $wpdb->delete( $table, array( 'BoatID' => $boatID ));

    if ($wpdb->last_error !== '') {
        $wpdb->print_error();
        $userMsg = "Sorry, had a problem getting data from the server for this boat.";
    } else {
        // 
        if ($boatsDelete = 0) {
            $userMsg = 'Did not delete a boat. Please try again.';  
        }
        elseif ($winningsDeleted > 0) {
            $userMsg = 'Deleted the boat and ' . $winningsDeleted . ' winnings.';
        }
        else {
            $userMsg = 'Deleted the boat';   
        }
        
        
    }


    $returnMsg = array(
        'UserMessage' =>  $userMsg
    );
    return $returnMsg;
}
