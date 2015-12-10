var Api = {

	/**
	 * General initialization
	 */
	run: function() {

		Api.events.chooseBox();
		Api.events.pastBilling();
	},

	/**
	 * Initializations
	 */
	init: {

	},

	/**
	 * Events handler
	 */
	events: {

		chooseBox: function() {

			// The user chose a box, we submit the form after having filled the hidden field
			$('#choose_box a').click(function(event) {

				event.preventDefault();

				var box_id = $(this).attr('id');
				$('#box_choice').attr('value', box_id);

				$('#choose_box').submit();

			});

		},

		pastBilling: function() {

			$('#copy_billing_informations').click(function(event) {

				event.preventDefault();

				$('#destination_first_name').attr('value', $('#billing_first_name').val());
				$('#destination_last_name').attr('value', $('#billing_last_name').val());
				$('#destination_city').attr('value', $('#billing_city').val());
				$('#destination_zip').attr('value', $('#billing_zip').val());

				$('#destination_address').val($('#billing_address').val());

			});


		},

	},

	/**
	 * Ajax calls
	 */
	call: {

		/*getJobsListFromDomain: function(domain_id, array_targets, old_job_selected) {

			$.post('/api/jobs-list-from-domain', {domain_id: domain_id}, function(feedback) {

				// We change the select for each target
				for (index in array_targets) {

					$(array_targets[index]).html('');

				}

				var groups = [];

				// In case there's no result
				if (typeof feedback[0] === "undefined") {

					for (index in array_targets) {

					target = array_targets[index];
					$(target).append('<option value="0">Choix du métier ...</option>');

					}

				}

				for (f_index in feedback) {

					id = feedback[f_index].id;
					content = feedback[f_index].content;

					if (typeof feedback[f_index].group !== "undefined") {

						group = feedback[f_index].group;

					} else {

						group = 0;

					}

					// We append the options for each target
					for (index in array_targets) {

						target = array_targets[index];

						if (typeof groups[target] === "undefined") {

							groups[target] = [];

						}

						if (typeof groups[target][group] === "undefined") {

							if (f_index > 2) {

								$(target).append('</optgroup>');

							}

							if (group != 0) {

							$(target).append('<optgroup label="' + group + '"></optgroup>');

							}

							groups[target][group] = true;

						}

						if (typeof old_job_selected == "undefined") {

							old_job_selected = 0;

						}

						$(target).append('<option value="' + id + '">' + content + '</option>');

					}

				}

				$(target).append('</optgroup>');

			});

		}*/

	},

};

$(document).ready(function() {

	Api.run();

});