// Supporting JavaScript for winners administration.
// Scott Nichols, 03/08/2021



jQuery(document).ready(function ($) {

	if($("#lyra_admin_trophies").length) {
		console.log("#lyra_admin_trophies");
	// **********************************************************************************
	// load the list of trophies.
	GetTrophies();

	// **********************************************************************************
	// On click of trophy listbox and year exit


	$("#TrophyListingDropDown").click(function (e) {
		e.preventDefault();
		console.log('TrophyListingDropDown on exit for Trophies page');

		if ($('#TrophyListingDropDown').val() === '-789876546') {
			clearForm(-999);
		} else {

			GetTrophy();
		}
	});


	// **********************************************************************************
	$("#butSaveTrophy").click(function (e) {
		e.preventDefault();

		console.log("butSaveTrophy");

		if($("#TrophyNameShort").val().length > 1) {

		var formData = {
			"action": "processTrophies",
			"activity": "SaveTrophy",
			'TrophyID': $('#TrophyID').val(),
			'TrophyNameShort': $('#TrophyNameShort').val(),
			'TrophyColumnLabel': $('#TrophyColumnLabel').val(),
			'LongDescription': $('#LongDescription').val(),
			'DeedOfGift': $('#DeedOfGift').val(),
			'RaceDetails': $('#RaceDetails').val(),
			'TrophyPageDisplayOrder': $('#TrophyPageDisplayOrder').val(),
			'PictureLink': $('#PictureLink').val(),
			'YearFirstAwarded': $('#YearFirstAwarded').val(),
			'YearRetired': $('#YearRetired').val()

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
		responseMessage('Trophy Name (Short) is required.')

	});


	$("#butDeleteTrophy").click(function (e) {
		e.preventDefault();

		if (confirm("Are you sure you want to delete? There is no UNDO!")) {
			if (confirm("Are you really, really sure you want to delete? There is no UNDO!")) {

				console.log("event.target.id", e.target.id);

				var row = (e.target.id).substr(-1);
				console.log("row", row);

				var formData = {
					"action": "processTrophies",
					"activity": "Delete",
					'TrophyID': $('#TrophyListingDropDown').val()
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
							setTimeout(() => { location.reload(); }, 5000);

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
		} else {
			responseMessage("Deletion canceled");
		}
	});


	//************************************************************************** */
	$("#butClearTrophy").click(function (e) {
		e.preventDefault();

		console.log('clearing');

		clearForm(-999);

	});



	//************************************************************************** */

	function GetTrophy() {
		// Get the list of winners for a year and trophy
		var formData = {
			"action": "processTrophies",
			"activity": "GetTrophy",
			'TrophyID': $('#TrophyListingDropDown').val(),
		};
		console.log("$('#TrophyListingDropDown').val()", $('#TrophyListingDropDown').val());
		responseMessage('Waiting for server...');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {
				console.log('response:' + JSON.stringify(response));

				if (response.success) {

					var trophy = jQuery.parseJSON(response.data.Trophy)
					if (trophy) {

						$('#TrophyID').val(trophy.TrophyID);
						$('#TrophyNameShort').val(trophy.TrophyNameShort);
						$('#TrophyColumnLabel').val(trophy.TrophyColumnLabel);
						$('#LongDescription').val(trophy.LongDescription);
						$('#DeedOfGift').val(trophy.DeedOfGift);
						$('#RaceDetails').val(trophy.RaceDetails);
						$('#PictureLink').val(trophy.PictureLink);
						$('#YearFirstAwarded').val(trophy.YearFirstAwarded);
						$('#YearRetired').val(trophy.YearRetired);

						var link2TrophyPage = '';
						var typePage = '';
						if (trophy.TrophyColumnLabel == 'Person') {
							typePage = "trophy-winners-nonboat";
						} else {
							typePage = "trophydetails";
						}
						link2TrophyPage = "../" + typePage + "/?trophyId=" + trophy.TrophyID;
						console.log(link2TrophyPage);
						$('#Link2TrophyPage').attr("href", link2TrophyPage);

					}
					responseMessage(response.data.UserMessage);

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


	//************************************************************************** */
	// Supporting functions

	// Get trophy listing and fill in listbox.
	function GetTrophies() {
		//e.preventDefault();

		var formData = {
			"action": "processTrophies",
			"activity": "GetTropiesList"
		};

		responseMessage('Waiting for server for trophies list...');

		$.ajax({
			type: "post",
			dataType: "json",
			url: myAjax.ajaxurl,
			data: formData,
			success: function (response) {
				//			console.log('response:' + JSON.stringify(response));
				if (response.success) {
					data = response.data;
					responseMessage(data.UserMessage);

					// Go through boats JSON and fill in SELECT 
					$("#TrophyListingDropDown").empty();
					$('#TrophyListingDropDown').append($('<option>', {
						value: '-789876546',
						text: ''
					}));

					var obj = jQuery.parseJSON(data.trophiesListing);
					$.each(obj, function (i, item) {
						//console.log('item.DisplayName:',item.DisplayName);
						$('#TrophyListingDropDown').append($('<option>', {
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


	function responseMessage(msg) {
		$('#responseMessage').html(msg);
		$('#responseMessage').fadeIn(100);
		$("#responseMessage").delay(3200).fadeOut(300);

	}


	function clearForm(temptrophyid) {
		$('#TrophyID').val(temptrophyid);
		$('#TrophyNameShort').val("");
		$('#TrophyColumnLabel').val("");
		$('#LongDescription').val("");
		$('#DeedOfGift').val("");
		$('#RaceDetails').val("");
		$('#TrophyPageDisplayOrder').val("");
		$('#PictureLink').val("");
		$('#YearFirstAwarded').val("");
		$('#YearRetired').val("");
	}
	}
});


