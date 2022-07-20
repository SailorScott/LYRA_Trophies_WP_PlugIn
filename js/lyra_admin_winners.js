// Supporting JavaScript for winners administration.
// Scott Nichols, 03/08/2021

const isEmpty = str => str== null || !str.trim().length;

jQuery(document).ready(function ($) {

	if($("#lyra_admin_winners").length) {
		console.log("#lyra_admin_winners");
	// **********************************************************************************
	// load the list of trophies and Boats.
	GetTrophies();
	GetBoats();



	// **********************************************************************************
	// On click of trophy listbox and year exit
	$("#Year").focusout(function (e) {
		e.preventDefault();
		// console.log('Year on exit', $("#Year").val());
		GetWinners();
	});

	$("#TrophyListing").click(function (e) {
		e.preventDefault();
		// console.log('TrophyListing on exit');
		GetWinners();
	});


	// **********************************************************************************
	$("[id^=butSave-]").click(function (e) {
		e.preventDefault();
		var row = (e.target.id).substr(-1);
		console.log("row", row);

		// console.log('BoatSkipper',parseInt($('#BoatListing-' + row).val().length), 'value-', parseInt($('#BoatListing-' + row).val()), '-');
		// console.log('TrophyListing',$('#TrophyListing').val(), 'value-', $('#TrophyListing').val(), '-');

		if ((parseInt($('#BoatListing-' + row).val()) !== -999) 
				|| !isEmpty($('#TrophyListing').val())
				|| !isEmpty($('#Year').val())
				) {
			//console.log("event.target.id", e.target.id);

			console.log('saving....');
			var formData = {
				"action": "processWinners",
				"activity": "Save",
				'BoatID': $('#BoatListing-' + row).val(),
				'regattaYear': $('#Year').val(),
				'TrophyID': $('#TrophyListing').val(),
				'OrigBoatID': $("#OrigBoatID-" + row).val(),
				'AwardedFor': $("#AwardedFor-" + row).val()

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
						// 			$("#BoatID").val(data.newBoatID);
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
		} else
		responseMessage('Please pick a boat! Or a Trophy! Or a Year!!');
	});


	$("[id^=butDelete-]").click(function (e) {
		e.preventDefault();

		if (confirm("Are you sure you want to delete? There is no UNDO!")) {

			console.log("event.target.id", e.target.id);

			var row = (e.target.id).substr(-1);
			console.log("row", row);

			var formData = {
				"action": "processWinners",
				"activity": "Delete",
				'BoatID': $('#BoatListing-' + row).val(),
				'regattaYear': $('#Year').val(),
				'TrophyID': $('#TrophyListing').val()

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
						// 			$("#BoatID").val(data.newBoatID);
						responseMessage(data.UserMessage);

						GetWinners();
					}
					else {
						alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
					}
				},
				error: function (errorThrown) {
					console.log(errorThrown);
				}

			});
		} else {
			responseMessage("Deletion canceled");
		}
	});



	function GetWinners() {
		// check that we have a year and trophy before sending to server

		if ($('#Year').val() && $('#TrophyListing').val()) {
			$("#SelectedTrohyYear").text("Winners for " + $("#TrophyListing option:selected").text() + " in " + $('#Year').val());

			// Get the list of winners for a year and trophy
			var formData = {
				"action": "processWinners",
				"activity": "GetWinners",
				'regattaYear': $('#Year').val(),
				'TrophyID': $('#TrophyListing').val(),
			};
			console.log("$('#TrophyListing').val()", $('#TrophyListing').val());
			responseMessage('Waiting for server...');

			$.ajax({
				type: "post",
				dataType: "json",
				url: myAjax.ajaxurl,
				data: formData,
				success: function (response) {
					console.log('response:' + JSON.stringify(response));

					if (response.success) {
						data = response.data;
						// clear values from all text boxes
						// hide all but first row
						for (let index = 0; index < 5; index++) {
							$("#BoatListing-" + index).val(-999);
							$("#AwardedFor-" + index).val('');

							// if (index > 0) {
							// 	$('#winnersRow-' + index).hide();
							// }
						}

						var lastTableRow = -1;
						var obj = jQuery.parseJSON(data.winners);
						$.each(obj, function (i, item) {
							$("#BoatListing-" + i).val(item.BoatID);
							$("#AwardedFor-" + i).val(item.AwardedFor);
							$("#OrigBoatID-" + i).val(item.BoatID);
							console.log("i", i);
							// $('#winnersRow-' + i).show();
							lastTableRow = i;
						});

						// $("#BoatListing-1").val(data.BoatID);
						responseMessage(data.UserMessage + ' Found ' + (lastTableRow + 1) + ' winners.');

						// if (lastTableRow < 4) {
						// 	$('#winnersRow-' + (lastTableRow + 1)).show();
						// }

					}
					else {
						alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
					}
				},
				error: function (errorThrown) {
					console.log(errorThrown);
				}

			});

		} else {
			responseMessage("Please check that you have a year entered, and trophy selected!");
		}

	}


	//************************************************************************** */
	// Supporting functions

	// Get trophy listing and fill in listbox.
	function GetTrophies() {
		//e.preventDefault();
		console.log('GetTrophies JS');

		var formData = {
			"action": "processWinners",
			"activity": "GetTropies"
		};

		responseMessage('Waiting for server for trophies list...');
		console.log('GetTrophies JS post responce message');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {
				//				console.log('response:' + JSON.stringify(response));
								// console.log('response:' + response);
				console.log('GetTrophies JS BEFORE IF ');
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
					console.log('GetTrophies JS IF FALSE');
					alert("Sorry, we had an error getting your data from the  server. If it reoccurs please send a note to the web admin.");
				}
			},
			error: function (errorThrown) {
				console.log('errorThrown:' + JSON.stringify(errorThrown));
			}

		});


	}

	// Get trophy listing and fill in listbox.
	function GetBoats() {
		//e.preventDefault();

		var formData = {
			"action": "processWinners",
			"activity": "GetBoats"
		};

		responseMessage('Waiting for server for boat names list...');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {
				//				console.log('response:' + JSON.stringify(response));
				if (response.success) {
					data = response.data;
					responseMessage(data.UserMessage);

					// Go through boats JSON and fill in SELECT 
					$("#BoatListing-0").empty();
					$('#BoatListing-0').append($('<option>', {
						value: -999,
						text: ''
					}));

					var obj = jQuery.parseJSON(data.boatsListing);
					$.each(obj, function (i, item) {
						$('#BoatListing-0').append($('<option>', {
							value: item.BoatID,
							text: item.DisplayName
						}));
					});

					// copy over select option.
					var options = $("#BoatListing-0").html();
					$('#BoatListing-1').html(options);
					$('#BoatListing-2').html(options);
					$('#BoatListing-3').html(options);
					$('#BoatListing-4').html(options);
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

	function responseMessage(msg) {
		$('#responseMessage').html(msg);
		$('#responseMessage').fadeIn(100);
		$("#responseMessage").delay(3200).fadeOut(300);

	}
	}
});
