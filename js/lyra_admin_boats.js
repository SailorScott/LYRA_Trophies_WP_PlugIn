// Supporting JavaScript for boat administration.
// Scott Nichols, 02/08/2021

jQuery(document).ready(function ($) {

	// **********************************************************************************
	$("#butSave").click(function (e) {
		e.preventDefault();

		var formData = {
			"action": "processBoat",
			"activity": "Save",
			'FilterBoatsList': $('#FilterBoatsList').val(),
			'BoatID': $('#BoatID').val(),
			'BoatName': $('#BoatName').val(),
			'Skipper': $('#Skipper').val(),
			'BoatType': $('#BoatType').val(),
			'HomeClub': $('#HomeClub').val()
		};

		responseMessage('Waiting for server...');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {

				if (response.success) {
					data = response.data;
					$("#BoatID").val(data.newBoatID);
					responseMessage(data.UserMessage);

				}
				else {
					alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
				}
			},
			error: function (errorThrown) {
				console.log(errorThrown);
			}

		});
	});
	//************************************************************************** */
	$("#butFind").click(function (e) {
		e.preventDefault();

		var formData = {
			"action": "processBoat",
			"activity": "Find",
			'FilterBoatsList': $('#FilterBoatsList').val(),
			'BoatName': '',
			'Skipper': '',
			'BoatType': '',
			'HomeClub': ''
		};
		responseMessage('Waiting for server...');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {
				if (response.success) {
					data = response.data;
					responseMessage(data.UserMessage);

					// Go through boats JSON and fill in SELECT 
					$("#boatListing").empty();
					var obj = jQuery.parseJSON(data.Boats);
					$.each(obj, function (i, item) {
						$('#boatListing').append($('<option>', {
							value: item.BoatID,
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

	});
	//************************************************************************** */
	$("#boatListing").click(function (e) {
		e.preventDefault();

		console.log('selected', $("#boatListing").val());

		var formData = {
			"action": "processBoat",
			"activity": "BoatDetails",
			'BoatID': $("#boatListing").val()
		};

	
		clearForm();

		responseMessage('Waiting for server...');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {
				// console.log('success?' + JSON.stringify(response));
				if (response.success) {
					// response = jQuery.parseJSON(response)
					data = response.data;
					responseMessage(data.UserMessage);

					// Go through boats JSON and fill in SELECT 
					$("#boatsWinning").empty();
					console.log('about to parse');

					var trophies = data.Trophies;
					$.each(trophies, function (i, item) {
						$('#boatsWinning').append($('<option>', {
							value: item.TrophyID,
							text: item.DisplayName
						}));
						console.log('item.DisplayName', item.DisplayName);
					});
					if (data.BoatDetail) {
						var boat = jQuery.parseJSON(data.BoatDetail)
						$('#BoatID').val(boat.BoatID);
						$('#BoatName').val(boat.BoatName);
						$('#Skipper').val(boat.Skipper);
						$('#BoatType').val(boat.BoatType);
						$('#HomeClub').val(boat.HomeClub);
					}
				}
				else {
					alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
				}
			},
			error: function (errorThrown) {
				console.log(errorThrown);
			}

		});

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

		if (!($('#BoatID').val() || $('#BoatName').val())) {
			alert("Missing enough information to delete the boat.");
			return;
		}

		// confirm action
		var alertMessage = $('#BoatName').val() + ` will be permanently deleted. Are you sure you wish to continue?`;
		if (!(confirm(alertMessage))) {
			responseMessage("Deletion canceled.");

		} else {
			responseMessage($('#BoatName').val() + ` will be permanently deleted.`);
			// send boat id to backend for deletion.


			var formData = {
				"action": "processBoat",
				"activity": "DeleteBoat",
				'BoatID': $("#boatListing").val()
			};
	

			$.ajax({
				type: "post",
				dataType: "json",
				url: myAjax.ajaxurl,
				data: formData,
				success: function (response) {
					// console.log('success?' + JSON.stringify(response));
					if (response.success) {
						// response = jQuery.parseJSON(response)
						data = response.data;
						responseMessage(data.UserMessage);
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

	});

	//************************************************************************** */
	// Supporting functions

	// Clear data entry fields.
	function clearForm() {

		//console.log('formData', formData);
		$('#BoatID').val("");
		$('#BoatName').val("");
		$('#Skipper').val("");
		$('#BoatType').val("");
		$('#HomeClub').val("");

	}

	function responseMessage(msg) {
		$('#responseMessage').html(msg);
		$('#responseMessage').fadeIn(100);
		$("#responseMessage").delay(3200).fadeOut(300);

	}

});
