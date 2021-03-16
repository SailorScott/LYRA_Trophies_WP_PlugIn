// Supporting JavaScript for winners administration.
// Scott Nichols, 03/08/2021

jQuery(document).ready(function ($) {
	// **********************************************************************************
	// load the list of trophies.
	GetTrophies();



	// **********************************************************************************
	$("#butSave").click(function (e) {
		e.preventDefault();

		// var formData = {
		// 	"action": "processBoat",
		// 	"activity": "Save",
		// 	'FilterBoatsList': $('#FilterBoatsList').val(),
		// 	'BoatID': $('#BoatID').val(),
		// 	'BoatName': $('#BoatName').val(),
		// 	'Skipper': $('#Skipper').val(),
		// 	'BoatType': $('#BoatType').val(),
		// 	'HomeClub': $('#HomeClub').val()
		// };

		// $('#responseMessage').html('Waiting for server...');
		// $('#responseMessage').fadeIn(100);

		// $.ajax({
		// 	type: "post",
		// 	dataType: "json",
		// 	url: myAjax.ajaxurl,
		// 	data: formData,
		// 	success: function (response) {

		// 		if (response.success) {
		// 			data = response.data;
		// 			$("#BoatID").val(data.newBoatID);
		// 			responseMessage(data.UserMessage);

		// 		}
		// 		else {
		// 			alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
		// 		}
		// 	},
		// 	error: function (errorThrown) {
		// 		console.log(errorThrown);
		// 	}

		// });
	});
	//************************************************************************** */
	$("#butFind").click(function (e) {
	

	});
	//************************************************************************** */
	$("#boatListing").click(function (e) {
		e.preventDefault();


	});

	//************************************************************************** */
	$("#butClear").click(function (e) {
		e.preventDefault();
		clearForm();
	});

	//************************************************************************** */
	$("#butDelete").click(function (e) {
		e.preventDefault();
		// check we have values.

	
	});

	//************************************************************************** */
	// Supporting functions

	// Get trophy listing and fill in listbox.
function GetTrophies() {
	//e.preventDefault();

	var formData = {
		"action": "processWinners",
		"activity": "GetTropies"
	};

	$('#responseMessage').html('Waiting for server for trophies list...');
	$('#responseMessage').fadeIn(100);

	$.ajax({
		type: "post",
		dataType: "json",
		url: myAjax.ajaxurl,
		data: formData,
		success: function (response) {
			console.log('response:' + JSON.stringify(response));
			if (response.success) {
				data = response.data;
				responseMessage(data.UserMessage);

				// Go through boats JSON and fill in SELECT 
				$("#TrophyListing").empty();
				var obj = jQuery.parseJSON(data.trophiesListing);
				$.each(obj, function (i, item) {
					$('#TrophyListing').append($('<option>', {
						value: item.TrophyID,
						text: item.DisplayName
					}));
				});
			}
			else {
				alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
			}
		},
		error: function (errorThrown) {
			console.log(errorThrown);
		}

	});


}


	// Clear data entry fields.
	function clearForm() {

		//console.log('formData', formData);
		// $('#BoatID').val("");
		// $('#BoatName').val("");
		// $('#Skipper').val("");
		// $('#BoatType').val("");
		// $('#HomeClub').val("");

	}

	function responseMessage(msg) {
		$('#responseMessage').html(msg);
		$('#responseMessage').fadeIn(100);
		$("#responseMessage").delay(3200).fadeOut(300);

	}


});
