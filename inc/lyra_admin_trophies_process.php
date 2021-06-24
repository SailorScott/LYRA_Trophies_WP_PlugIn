<?php
class Trophies
{

    public $TrophyID;
    public $TrophyNameShort;
    public $TrophyColumnLabel;
    public $LongDescription;
    public $DeedOfGift;
    public $RaceDetails;
    public $TrophyPageDisplayOrder;
    public $PictureLink;
    public $YearFirstAwarded;
    public $YearRetired;



    public static function process()
    {
        // A default response holder, which will have data for sending back to our js file
        $response = array(
            'error' => false,
        );

        $trophyData = new Trophies();

        $activity = $_POST['activity']; // front end action request. CRUD
        $trophyData->TrophyID = $_POST['TrophyID'] ?? -999;
        $trophyData->TrophyNameShort = $_POST['TrophyNameShort'] ?? '';
        $trophyData->TrophyColumnLabel = $_POST['TrophyColumnLabel'] ?? '';
        $trophyData->LongDescription = $_POST['LongDescription'] ?? '';
        $trophyData->DeedOfGift = $_POST['DeedOfGift'] ?? '';
        $trophyData->RaceDetails = $_POST['RaceDetails'] ?? '';
        $trophyData->TrophyPageDisplayOrder = $_POST['TrophyPageDisplayOrder'] ?? '';

        $trophyData->PictureLink = $_POST['PictureLink'] ?? '';
        $trophyData->YearFirstAwarded = $_POST['YearFirstAwarded'] ;
        $trophyData->YearRetired = $_POST['YearRetired'];

        $trophyData->YearFirstAwarded = $trophyData->checkYear($trophyData->YearFirstAwarded);
        $trophyData->YearRetired = $trophyData->checkYear($trophyData->YearRetired);


        //   error_log('FormData' . $trophyData);

        switch ($activity) {
            case 'GetTropiesList':
                $returnMsg = $trophyData->TrophiesListing();
                break;


            case 'GetTrophy':
                $returnMsg = $trophyData->Trophy($trophyData);
                break;
            case 'SaveTrophy':
                if ($trophyData->TrophyID == -999 || $trophyData->TrophyID = 'New') {
                    $returnMsg = $trophyData->insert($trophyData);
                } else {
                    $returnMsg = $trophyData->update($trophyData);
                }

                break;

            case 'Delete':
                // get the trophy details and any trophies the won.
                $returnMsg = $trophyData->delete($trophyData);
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



    function Trophy(Trophies $postData = null)
    {
        global $wpdb;

        $dbdata = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT TrophyID, 
                TrophyNameShort, 
                TrophyColumnLabel, 
                LongDescription, 
                DeedOfGift, 
                RaceDetails, 
                TrophyPageDisplayOrder, 
                PictureLink, 
                YearFirstAwarded, 
                YearRetired
            FROM " .  $wpdb->prefix . "lyra_trophies 
            WHERE TrophyID = %d",

                $postData->TrophyID
            )
        );

        // convert into JSON
        $data = json_encode($dbdata);

        $userMsg = 'Loaded trophy data.';

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem getting trophy from database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'Trophy' => $data,
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }



    //********************************************************************************** */
    // update Trophies, 

    function update($trophyData)
    {
        global $wpdb;

        $wpdb->update(
            $wpdb->prefix . "lyra_trophies",
            array(
                'TrophyNameShort' => $trophyData->TrophyNameShort,
                'TrophyColumnLabel' => $trophyData->TrophyColumnLabel,
                'LongDescription' => $trophyData->LongDescription,
                'DeedOfGift' => $trophyData->DeedOfGift,
                'RaceDetails' => $trophyData->RaceDetails,
                'TrophyPageDisplayOrder' => $trophyData->TrophyPageDisplayOrder,
                'PictureLink' => $trophyData->PictureLink,
                'YearFirstAwarded' => $trophyData->YearFirstAwarded,
                'YearRetired' => $trophyData->YearRetired
            ),
            array('TrophyID' => $trophyData->TrophyID)

        );

        $TrophyName = $wpdb->get_var(
            "SELECT  TrophyNameShort 
            FROM " .  $wpdb->prefix . "lyra_trophies
            WHERE TrophyID =" . $trophyData->TrophyID
        );

        $userMsg =   "Updated <i>" . $TrophyName . "</i> information.";

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem adding trophy to database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }


   //********************************************************************************** */
    // Insert new Trophy
    
    function insert($trophyData)
    {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . "lyra_trophies",
            array(
                'TrophyNameShort' => $trophyData->TrophyNameShort,
                'TrophyColumnLabel' => $trophyData->TrophyColumnLabel,
                'LongDescription' => $trophyData->LongDescription,
                'DeedOfGift' => $trophyData->DeedOfGift,
                'RaceDetails' => $trophyData->RaceDetails,
                'TrophyPageDisplayOrder' => $trophyData->TrophyPageDisplayOrder,
                'PictureLink' => $trophyData->PictureLink,
                'YearFirstAwarded' => $trophyData->YearFirstAwarded,
                'YearRetired' => $trophyData->YearRetired
            ));

        $TrophyName = $wpdb->get_var(
            "SELECT TrophyNameShort 
            FROM " .  $wpdb->prefix . "lyra_trophies
            ORDER BY TrophyID DESC LIMIT 1"
        );

        $userMsg =   "Added <i>" . $TrophyName . "'s</i> information to the datebase.";

        if ($wpdb->last_error !== '') :
            $wpdb->print_error();
            $userMsg = "Error: Problem adding trophy to database. Please see the error log for more details.";
        endif;

        $returnMsg = array(
            'UserMessage' =>  $userMsg
        );


        return $returnMsg;
    }

    //********************************************************************************** */
    // Boat Detials
    function delete($trophyData)
    {
        global $wpdb;

        $trophyCount = 0;
        $winnersCount = 0;
        $winnersNonBoat = 0;

        $TrophyName = $wpdb->get_var(
            "SELECT  TrophyNameShort 
            FROM " .  $wpdb->prefix . "lyra_trophies
            WHERE TrophyID =" . $trophyData->TrophyID
        );

        // delete from winners tables
        $table = $wpdb->prefix . "lyra_trophywinners";
        $winnersCount = $wpdb->delete($table, array(
            'TrophyID' => $trophyData->TrophyID
        ));

        $table = $wpdb->prefix . "lyra_trophywinners_notboats";
        $winnersNonBoat = $wpdb->delete($table, array(
            'TrophyID' => $trophyData->TrophyID
        ));

        // delete from main trophies table
        $table = $wpdb->prefix . "lyra_trophies";
        $trophyCount = $wpdb->delete($table, array(
            'TrophyID' => $trophyData->TrophyID
        ));


        if ($wpdb->last_error !== '') {
            $wpdb->print_error();
            $userMsg = "Sorry, had a problem deleting data from the server for this trophy.";
        } else {
            // 
            if ($trophyCount = 0) {
                $userMsg = 'Did not delete a trophy. Please try again.';
            } else {
                $userMsg = 'Deleted ' . $TrophyName . ' from list of Trophies. Which also removed ' . ($winnersCount + $winnersNonBoat) . ' winners. To recover you will need to restore a backup copy of the database.';
            }
        }


        $returnMsg = array(
            'UserMessage' =>  $userMsg
        );
        return $returnMsg;
    }

    function checkYear($year = null)
    {
        if (is_numeric($year)) {
            if ($year < 1880 || $year> (getYear() + 1)) {
                return null;
            } else {
                return $year;
            }
        } else {
            return null;
        }
    }

}
