<?php


function lyra_admin_trophies()
{
    echo "
    <div id='lyra_admin_trophies' class='LyraInputBlock'>
        <label  class='LyraInput' for='TrophyListingDropDown'>
            <span >Select Trophy</span>
            <select id='TrophyListingDropDown'>
            </select>
        </label>

 
        <hr/>
 
        <label class='LyraInput' for='TrophyID'><span >TrophyID</span><input type='text' id='TrophyID' readonly value='-999'>   <button id='butClearTrophy' class='button action'>New (clear form)</button></label>
        <h3><a id='Link2TrophyPage' target='_blank' href=''>Current Winners Display Page</a></h3>
       <label class='LyraInput' for='TrophyNameShort'><span >Name (Short)*</span><input type='text' id='TrophyNameShort' placeholder='Name shown on lists' required></label>
        <label class='LyraInput' for='TrophyColumnLabel'><span >Trophy Type</span><Select id='TrophyColumnLabel'><option>Boat</option><option>Person</option><option>Flag</option> </select></label>
        <label class='LyraInput' for='LongDescription'><span >Description</span><textarea id='LongDescription' placeholder='Long description for trophy's page.'></textarea></label>
        <label class='LyraInput' for='DeedOfGift'><span >Deed Of Gift</span><input type='text' id='DeedOfGift' placeholder='Deed of Gift'></label>
        <label class='LyraInput' for='RaceDetails'><span >Race Details</span><textarea id='RaceDetails' placeholder=''></textarea></label>
        <label class='LyraInput' for='TrophyPageDisplayOrder'><span >Display Order</span><input type='text' id='TrophyPageDisplayOrder' placeholder='Trophy page display order'></label>
        <label class='LyraInput' for='PictureLink'><span >Picture Link</span><input type='text' id='PictureLink' placeholder='Upload into Media and get URL'>Look in Media Library and copy url starting with '/wp-content/...'.</label>
        <label class='LyraInput' for='YearFirstAwarded'><span >First Awarded</span><input type='text' id='YearFirstAwarded' placeholder='Year first awarded'></label>
        <label class='LyraInput' for='YearRetired'><span >Year Retired</span><input type='text' id='YearRetired' placeholder=''></label>
        
        <button id='butSaveTrophy' class='button action'>Save Trophy Info</button> 
        <span class='buttonRight'>&nbsp;</span>
        <button id='butDeleteTrophy' class='button action' >Delete Trophy and associated winners</button> 

</div>    


    <div id='responseMessage'></div>
";
}