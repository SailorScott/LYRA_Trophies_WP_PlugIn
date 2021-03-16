<?php


function lyra_admin_winners()
{
    echo "
<div class='WinnersRow'>
    <div class='WinnersColumn WinnersLeftBlock'>
    
        <label class='LyraInput' for='Year'>
            <span>Year</span>
            <input type='text' name='Year' id='Year' placeholder='Enter year of event'>
        </label>

        <label  class='LyraInput' for='TrophyListing'>
            <span >Select a Trophy to assign winners</span>
            <select id='TrophyListing' size=30 >
            </select>
        </label>

  </div>

 
    <div class='WinnersColumn WinnersRightBlock'>
    
    <div id='SelectedTrohyYear'>Please enter a year and pick a trophy.</div>

    <table>
        <tr>
            <th>Boat and Skipper</th><th>Awarded For</th><th></th>
        </tr>

        <tr>
            <td> 
                <select id='BoatListing-1' > !!!! Load boats and then get records.
                </select>
            </td>
            <td><input type='text' name='AwardedFor' id='AwardedFor' placeholder='Awarded For'>
            </td>
            <td><button id='butSave' class='button action'>Save Boat Info</button> </td>
        </tr>
        <tr>
            <td>Boat and Skipper</td><td>Awarded For</td>
            <td></td>
        </tr>
    </table>

    
    
    </div>
 

</div>    


    <div id='responseMessage'></div>
";
}

