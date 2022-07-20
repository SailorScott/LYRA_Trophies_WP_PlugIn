<?php


function lyra_admin_winners()
{
    echo "
<div id='lyra_admin_winners' class='WinnersRow'>
    <div class='WinnersColumn WinnersLeftBlock'>
    
        <label class='LyraInput' for='Year'>
            <span>Year</span>
            <input type='text' name='Year' id='Year' placeholder='Enter year of event'>
        </label>

        <label  class='LyraInput' for='TrophyListing'>
            <span >Select a Trophy to assign winners</span>
            <br/>
            <select  id='TrophyListing' size='8' >
  
            </select>
        </label>

  </div>

 
    <div class='WinnersColumn WinnersRightBlock'>
    
    <div id='SelectedTrohyYear'>Please enter a year and pick a trophy.</div>

    <table>
        <tr>
            <th>Boat and Skipper</th><th>Awarded For</th><th></th>
        </tr>

        <tr id='winnersRow-0'>
            <td> 
                <select id='BoatListing-0' > 
                </select>
            </td>
            <td><input type='text' id='AwardedFor-0'>
            <input type='hidden' id='OrigBoatID-0'>
            </td>
            <td><button id='butSave-0' class='button action'>Save</button>
            <button id='butDelete-0' class='button action'>Delete</button> </td>
    </tr>

        <tr id='winnersRow-1'>
            <td> 
                <select id='BoatListing-1' > 
                </select>
            </td>
            <td><input type='text' id='AwardedFor-1'>
            <input type='hidden' id='OrigBoatID-1'>
            </td>
            <td><button id='butSave-1' class='button action'>Save</button>
            <button id='butDelete-1' class='button action'>Delete</button> </td>
        </tr>
        
        <tr id='winnersRow-2'>
            <td> 
                <select id='BoatListing-2' > 
                </select>
            </td>
            <td><input type='text' id='AwardedFor-2'>
            <input type='hidden' id='OrigBoatID-2'>
            </td>
            <td><button id='butSave-2' class='button action'>Save</button>
            <button id='butDelete-2' class='button action'>Delete</button> </td>
        </tr>

        <tr id='winnersRow-3'>
            <td> 
                <select id='BoatListing-3' > 
                </select>
            </td>
            <td><input type='text' id='AwardedFor-3'>
            <input type='hidden' id='OrigBoatID-3'>
            </td>
            <td><button id='butSave-3' class='button action'>Save</button>
            <button id='butDelete-3' class='button action'>Delete</button> </td>
        </tr>


        <tr id='winnersRow-4'>
            <td> 
                <select id='BoatListing-4' > 
                </select>
            </td>
            <td><input type='text' id='AwardedFor-4'>
            <input type='hidden' id='OrigBoatID-4'>
            </td>
            <td><button id='butSave-4' class='button action'>Save</button>
            <button id='butDelete-4' class='button action'>Delete</button> </td>
        </tr>


    </table>

    <p>Use 'Not Awarded' if the trophy was not awarded, and use Awarded For field to give reason.</p>
    
    </div>
 

</div>    


    <div id='responseMessage'></div>
";
}