<?php


function lyra_admin_boats()
{
    echo "<div class='LyraInputBlock'>

    <label class='LyraInput' for='FilterBoatsList'>
        <span>Filter</span>
        <input class='input' width = 100px type='text' id='FilterBoatsList' title='Enter in at least 2 characters. ** for all.' placeholder='Filter boats listing by Name or Skipper'>
        <button id='butFind' class='button action'>Find</button> 

        </label>

    <label  class='LyraInput' for='boatListing'>
    <span >Select a boat<br>to edit</span>
    <select id='boatListing' size='8' >
    
    </select>
    </label>

    <label  class='LyraInput' for='boatsWinning'>
    <span>Awards</span>
    <select id='boatsWinning' size='3'  disabled='true'>
    
    </select>
    </label>

<form class='wordpress-ajax-form' id='lyraForm' method='post' action='" . admin_url('admin-ajax.php') . "'>

<label class='LyraInput' for='BoatID'>
    <span >Boat Id</span>
    <input type='text' id='BoatID' readonly>
    <button id='butClear' class='button action'>New (clear form)</button>
</label>
<label class='LyraInput' for='BoatName'><span>Boat Name</span><input type='text' name='BoatName' id='BoatName' placeholder='Please enter boat name'></label>
<label class='LyraInput' for='Skipper'><span>Skipper</span><input type='text' name='Skipper' id='Skipper' placeholder='Skippers name'></label>
<label class='LyraInput' for='BoatType'><span >Boat Make</span><input type='text' name='BoatType' id='BoatType' placeholder='Boat make and model'></label>
<label class='LyraInput' for='HomeClub'><span >Home Club</span><input type='text' name='HomeClub' id='HomeClub' placeholder='Home Yacht Club'></label>
    
<button id='butSave' class='button action'>Save Boat Info</button> 

<span class='buttonRight'>&nbsp;</span>
<button id='butDelete' class='button action' >Delete Boat and associated Trophies</button> 
<div id='responseMessage'></div>

</form>
    </div>";
}

