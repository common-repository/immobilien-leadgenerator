function prt_js_root(isMobile) {
	// if already called dont run this function. (bugfix for js-omptimizer codes like jch optimize)
	if(prt_root_already_called) return;
	
	prt_root_already_called = true;

	var prtIsMobile = isMobile || false;

	var requestData = {
		//empty
	};
	window.prtRequestData = requestData;

	var prtInputField = document.getElementsByClassName('prtAdressInputField');
	var prtGoogleAutocompleteOptions = { componentRestrictions: { country: 'de' } };
	var prtGoogleAutocomplete = [];
	
	// Check for Google Places
	if(typeof google.maps.places !== "undefined") {
		for (var prt_i = 0; prt_i < prtInputField.length; prt_i++) {
			prtGoogleAutocomplete[prt_i] = new google.maps.places.Autocomplete(prtInputField[prt_i], prtGoogleAutocompleteOptions);
		}
	} else {
		console.warn("PRT: Couldn't load Google Maps Places into input field.");
	}

	Number.prototype.formatMoney = function (c, d, t) {
		var n = this,
			c = isNaN(c = Math.abs(c)) ? 2 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
			j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	var loadedSteps,
		stepsCount,
		currentStep = 0;

	var owl    = jQuery("#prt-root .owl-carousel");
	var owlObj = owl.owlCarousel({
		items: 1,
		loop: false,
		mouseDrag: false,
		touchDrag: false,
		pullDrag: false,
		freeDrag: false,
		rewind: false,
		nav: false,
		dots: false,
		autoplay: false,
		checkVisible: false,
		autoHeight: true,

		onInitialized: function() {
			jQuery("#prt-root .owl-carousel").trigger('refresh.owl.carousel');
		}
	});

	owl.on('changed.owl.carousel', function(event) {
		currentStep = event.item.index;
	})

	var dialogOpen = false;
	var loaderOpen = false;
	var dialogCancelCb, dialogNextCb;

	function openDialog(title, content, emoji) {
		if(prtIsMobile) { alert(content); return; }

		//bug fix: close on prt click
		setTimeout(function () {
			var oldEmoji = emoji;
			emoji = emoji || '';
			var $dialog = jQuery('.dialog');
			var $overlay = jQuery('.prt-overlay');

			jQuery('.dialog-header', $dialog).text(title);
			jQuery('.dialog-body > .text', $dialog).html(content);
			if (oldEmoji != false) jQuery('.dialog-body > .dialog-emoji', $dialog).text(emoji);

			$dialog.addClass('active');
			$overlay.addClass('active');
			jQuery('.dialog-emoji').addClass('active');

			dialogOpen = true;
		}, 50);
	}

	function closeDialog() {
		if(prtIsMobile) return;

		if (dialogOpen) {
			jQuery('.dialog').removeClass('active');
			jQuery('.dialog').removeClass('privacy-active');
			jQuery('.prt-overlay').removeClass('active');
			jQuery('.dialog-emoji').removeClass('active');
			dialogOpen = false;
		}
	}

	/**
	 * Will call openDialog but will add confirm features.
	 */
	function openConfirmDialog(title, content, mobileText, nextCallback, cancelCallback) {
		if (prtIsMobile) {
			if (mobileText == null) mobileText = content;
			if (confirm(mobileText)) nextCallback();
			else cancelCallback();
			
			return;
		}

		content = content || '';
		content += '<div class="prt-confirm-dialog-buttons">';
		content += '<button type="button" class="prt-button hvr-bounce-to-left prt-left prt-red prt-cancel-cb">Korrigieren</button>';
		content += '<button type="button" class="prt-button hvr-bounce-to-right prt-right prt-green prt-next-cb">Fortfahren</button>';
		content += '</div>';

		dialogNextCb = nextCallback;
		dialogCancelCb = cancelCallback;

		openDialog(title, content);
	}

	function openLoader() {
		jQuery('.loader').addClass('active');
		jQuery('.prt-overlay').addClass('active');
		loaderOpen = true;
	}

	function closeLoader() {
		if (loaderOpen) {
			jQuery('.loader').removeClass('active');
			jQuery('.prt-overlay').removeClass('active');
			loaderOpen = false;
		}
	}

	function checkForData(e) {
		var $step = jQuery(e).closest('.step');
		if ($step == undefined) return false;
		var $dataEle = $step.find('.data[data-key]');
		if ($dataEle == undefined) return false;

		if ($dataEle[0] && $dataEle[0].hasAttribute('data-has-input')) {
			if ($dataEle.attr('data-has-input') == 'false') {
				var dv = $dataEle.find('.selected[data-value]');
				if (dv.length) {
					var key = $dataEle.attr('data-key').trim();
					requestData[key] = dv.attr('data-value');
					return true;
				}
				return false;
			}
		}

		var inputs = $step.find('input, select');
		var success = true;
		inputs.each(function () {
			var $in = jQuery(this);
			if ($in[0].hasAttribute('required') && $in.val().trim() == "") {
				success = false;
				return false;
			}
			if ($in[0].classList.contains('privacy-read')) {
				success = jQuery($in[0]).is(':checked');
				return success;
			}
			var key = $in.attr('name').trim();
			requestData[key] = $in.val();
		});

		return success;
	}

	function emptyDone() {
		return { done: function (cb) { cb(); } }
	}

	function getTotalSlides() {
		return jQuery('.steps .step').length;
	}

	function slideNext() {
		owl.owlCarousel('next');
	}

	function slidePrev() {
		owl.owlCarousel('prev');
	}

	function slideGoTo(index) {
		owl.owlCarousel('next', index);
	}

	function slideRemove(index) {
		owl.owlCarousel('remove', index);
	}

	function slideAdd(newSlide, index) {
		owl.owlCarousel('add', newSlide, index);
	}

	function slideResizeTrigger() {
		setTimeout(function() {
			owl.trigger('refresh.owl.carousel');
		}, 100);
	}

	function fillResults(results) {
		jQuery('#prt-root .result').css('display', 'block');
		jQuery('#prt-root .prt-resultAbsolute').text(parseFloat(results.resultAbsolute).formatMoney(2, ',', '.') + ' €');
		jQuery('#prt-root .prt-resultPerSqm').text(parseFloat(results.resultPerSqm).formatMoney(2, ',', '.') + ' €');
		if (results.no_show_span) {
			jQuery('#prt-root .no-show-span').remove();
		} else {
			jQuery('#prt-root .prt-lowAbsolute').text(parseFloat(results.lowAbsolute).formatMoney(2, ',', '.') + ' €');
			jQuery('#prt-root .prt-highAbsolute').text(parseFloat(results.highAbsolute).formatMoney(2, ',', '.') + ' €');
			jQuery('#prt-root .prt-lowPerSqm').text(parseFloat(results.lowPerSqm).formatMoney(2, ',', '.') + ' €');
			jQuery('#prt-root .prt-highPerSqm').text(parseFloat(results.highPerSqm).formatMoney(2, ',', '.') + ' €');
		}
	}

	function no_rate_display() {
		jQuery('#prt-root .no_rate').css('display', 'block');
	}

	function not_found_display() {
		jQuery('#prt-root .prt_not_found').css('display', 'block');
	}

	function getAllOtherStepsByType(type, atIndex) {
		if (loadedSteps === type) return emptyDone();
		loadedSteps = type;
		return jQuery.post(prt_ajax_object.ajax_url, { action: 'prt_getsteps', type: type }, function (data) {

			var totalSteps = getTotalSlides();
			if (totalSteps !== 4) {
				for (var i = 1; i < (totalSteps - 3); i++) {
					slideRemove(1);
				}
			}

			stepsCount = data.length + 2; //+1 'cause forms + fail OR succes step

			for (var i = 0; i < data.length; i++) {
				var element = jQuery.parseHTML(data[i])[0];
				slideAdd(element, (i + 1));
			}

			requestData = {type: type};
			window.prtRequestData = requestData;
		}, 'json');
	}

	function validateAddress(e) {
		var $step = jQuery(e).closest('.step');
		var $addressInput = jQuery('.prtAdressInputField', $step);
		var successful = false;
		if ($addressInput.length) {

			openLoader();

			var jqxhr = jQuery.ajax({
				type: "POST",
				url: prt_ajax_object.ajax_url,
				async: true,
				data: { action: 'prt_geo', address: $addressInput.val() },
				dataType: 'json'
			});

			jqxhr.done(function (data) {
				if (data != null) {

					// Check is partial_match
					if(data.partial_match === true) {
						closeLoader();
						openConfirmDialog(
							'Sind sie sicher?',
							'Ihre eingegebene Adresse konnten wir nicht finden. Stattdessen fanden wir: ' +
							'<u><i>'+data.full_address+'</i></u>.<br>Möchten Sie mit dieser Adresse fortfahren?',
							'Ihre eingegebene Adresse konnten wir nicht finden. Stattdessen fanden wir: '+data.full_address+'. Möchten Sie mit dieser Adresse fortfahren?',

							function() {
								requestData['address'] = data.full_address;
								slideNext();
							}
						);
						return;
					}

					if (data.status == "success") {
						successful = true;
						closeLoader();
						if (checkForData(e)) slideNext();
						else openDialog('Fehler', 'Bitte füllen Sie alle erforderlichen Felder aus.');

					} else {
						closeLoader();

						if (!checkForData(e)) {
							openDialog("Adresse falsch!", "Bitte geben Sie eine Adresse ein!");
						} else {
							if (data.do_confirm) {
								openConfirmDialog('Adresse nicht gefunden!', data.error, null, function() {
									if (checkForData(e)) {
										requestData['force_no_rate'] = true;
										slideNext();
									}
									else openDialog('Fehler', 'Bitte füllen Sie alle erforderlichen Felder aus.');
								});
							} else {
								openDialog("Adresse falsch!", data.error);
							}
						}
					}
				}
			});

			jqxhr.fail(function () {
				closeLoader();
				openDialog("Adresse falsch!", "Bitte geben Sie eine richtige Adresse ein!");
			});
		}
		return successful;
	}

	function calcProgress() {
		if (stepsCount) {
			var percent = (currentStep / stepsCount) * 100;
			if (percent >= 100) percent = 100;
			var percentShow = Math.round(percent);
			var progress = jQuery('.progress-bar > .progress');
			progress.css('width', percent + '%');
			progress.text(percentShow + '%');
		}
	}

	function showErrorOnLastStep(errorMsg) {
		openDialog("Fehler", errorMsg);
	}

	function realEstateTypeDisabler() {
		if (requestData['realEstateTypeMiete'] != undefined) {
			if (requestData['realEstateTypeMiete'] == 1) { //1 = haus
				jQuery('#prt-root .opt-cat-wohnung').css('display', 'none').attr('disabled', 'disabled');
				jQuery('#prt-root .opt-cat-haus, #prt-root .opt-cat-wohnung.opt-cat-haus').css('display', 'block').removeAttr('disabled');
			} else {
				jQuery('#prt-root .opt-cat-haus').css('display', 'none').attr('disabled', 'disabled');
				jQuery('#prt-root .opt-cat-wohnung, #prt-root .opt-cat-wohnung.opt-cat-haus').css('display', 'block').removeAttr('disabled');
			}
		}
	}

	function gaPrtSend(label, value) {
		prtSendToStatistics(label, value === 100); //if value = 100 so its finished

		if (typeof ga !== 'undefined')
			ga('send', 'event', 'Wertermittlung', 'click', label, value);
		if (value === 100 && typeof prtGAdwordsOn !== 'undefined' && prtGAdwordsOn) {
			var image = new Image(1, 1);
			image.src = '//www.googleadservices.com/pagead/conversion/' + prtGAdwords.conversionId + '/?label=' + prtGAdwords.conversionLabel + '&guid=ON&script=0';
		}
	}

	function prtSendToStatistics(label, finished) {
		finished = (typeof finished !== 'undefined') ? finished : false;
		jQuery.post(prt_ajax_object.ajax_url,
			{ action: 'prt_statistic', current: label, finished: finished }, function (data) { });
	}

	function getCurrentActivity() {
		var stepC = currentStep - 1;
		if (requestData.type === undefined) return 'Anfang';
		else return requestData.type + ": Step " + (stepC < 0 ? 0 : stepC);
	}

	function googleMapInit() {
		var germany = { lat: 52.520007, lng: 13.404954 };
		var map = new google.maps.Map(document.getElementById('prt-google-map'), {
			zoom: 13,
			center: germany
		});
		googleMapsOnChange(map);
	}

	function fillInAddress(autocomplete) {
		// Get the place details from the autocomplete object.
		var place = autocomplete.getPlace();
		var addressTypes = {};

		if(typeof place.address_components !== 'undefined') {
			for (var i = 0; i < place.address_components.length; i++) {
				var addressType = place.address_components[i].types[0];
				switch (addressType) {
					case 'street_number':
					case 'route':
					case 'postal_code':
					case 'locality':
						addressTypes[addressType] = place.address_components[i];
						break;
				
					default:
						break;
				}
			}
		}

		var respValue = "";
		if(addressTypes.route)  respValue += addressTypes.route.short_name + ' ';
		if(addressTypes.street_number)  respValue += addressTypes.street_number.short_name;
		if(respValue.length > 0) respValue += ', ';
		if(addressTypes.postal_code)  respValue += addressTypes.postal_code.long_name + ' ';
		if(addressTypes.locality)  respValue += addressTypes.locality.long_name;

		prtInputField[0].value = respValue;
	}

	var prt_gmaps_timeout;
	var prt_gmaps_marker;
	function googleMapsOnChange(map) {/*
		jQuery('.prtAdressInputField').on('change input blur', function() {
			clearTimeout(prt_gmaps_timeout);
			prt_gmaps_timeout = setTimeout(function() {
				var gmPlace = prtGoogleAutocomplete[0].getPlace();
				if(typeof gmPlace !== 'undefined') {
					var infoWindow = new google.maps.InfoWindow();
					prt_gmaps_marker = new google.maps.Marker({
						map: map,
						position: gmPlace.geometry.location
					});
					infoWindow.setContent(gmPlace.adr_address);
					infoWindow.open(map, prt_gmaps_marker);
					map.setZoom(17);
					map.panTo(prt_gmaps_marker.position);
				} else console.log("yep", gmPlace);
			}, 200);
		});*/

		var autocomplete = prtGoogleAutocomplete[0];
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			fillInAddress(autocomplete);

			gmPlace = autocomplete.getPlace();
			var infoWindow = new google.maps.InfoWindow();
			prt_gmaps_marker = new google.maps.Marker({
				map: map,
				position: gmPlace.geometry.location
			});
			infoWindow.setContent(gmPlace.adr_address);
			infoWindow.open(map, prt_gmaps_marker);
			map.setZoom(18);
			map.panTo(prt_gmaps_marker.position);
		});
	}

	function prtSendMails(_id) {
		jQuery.post(prt_ajax_object.ajax_url, { action: 'prt_sendmails', _id : _id }, function(data) {
			if(data.status != 'success') {
				errorStr = data.error ? data.error : data.msg + ' - ' + data.extra_message;
				console.warn("Couldn't send mails. Error: ", errorStr);
			}
		}, 'json');
	}

	function clickHandlerLoad(clickedButton) {
		if (checkForData(clickedButton)) {
			if (!requestData.type.length) return false;
			openLoader();

			// GA-Track
			gaPrtSend(requestData.type + ": Step 1");

			var xhr = getAllOtherStepsByType(requestData.type, 1);
			xhr.done(function () {
				prtRangeSliderInit();
				closeLoader();
				slideNext();
				slideResizeTrigger();
				calcProgress();
				onMieteControl(requestData.type);
			});
		} else {
			openDialog('Fehler', 'Bitte wählen Sie eine Immobilie aus!');
		}
		return true;
	}

	function clickHandlerNext(clickedButton) {
		if (checkForData(clickedButton)) {
			realEstateTypeDisabler();
			slideNext();
			slideResizeTrigger();
			gaPrtSend(getCurrentActivity(), (currentStep - 1)); // GA-Track
		} else openDialog('Fehler', 'Bitte füllen Sie alle erforderlichen Felder aus.');
	}

	function onMieteControl(type) {
		if(type.toLowerCase() == 'miete') {
			jQuery('.prt-onnonmiete').css('display', 'none');
			jQuery('.prt-onmiete').css('display', 'block');
		}
	}

	function prtRangeSliderInit() {
		jQuery('#prt-root.theme-modern .prt-range-slider').each(function(i,e) {
			if(typeof e.noUiSlider !== 'undefined') return true;

			var $this = jQuery(e),
				input = $this.closest('section').find('.range-show-value').get(0),
				showUnderAbove = e.getAttribute('data-showUnderAbove'), // shows min. < and max. > IF true
				showM2 = e.getAttribute('data-showM2');

			var argMin  = parseFloat(input.min || 0),
				argMax  = parseFloat(input.max || 100),
				argStep = parseFloat(input.step || 1),
				argVal  = parseFloat(input.value || 50),
				argPips  = parseFloat(input.getAttribute('data-pips') || 5);

			noUiSlider.create(e, {
				step: argStep,
				range: {
					min: argMin,
					max: argMax
				},
				start: [ argVal ],
				pips: { mode: 'count', values: argPips, stepped: true }
			});

			e.noUiSlider.on('update', function( values, handle ) {
				input.value = parseFloat(values[handle]);
			});

			jQuery(input).on('change', function() {
				e.noUiSlider.set(parseFloat(this.value));
			});

			var firstPip = jQuery('.noUi-value.noUi-value-large:first', e),
				lastPip  = jQuery('.noUi-value.noUi-value-large:last', e);

			if (showUnderAbove) {
				firstPip.text('< ' + firstPip.data('value'));
				lastPip.text('> ' + lastPip.data('value'));
			}
			if (showM2) {
				firstPip.text(firstPip.text() + 'm²');
				lastPip.text(lastPip.text() + 'm²');
			}
			
		});
	}

	var prtEmulateTimeout;
	function startEmulateTimeout() {
		if(prtEmulateTimeout) clearTimeout(prtEmulateTimeout);

		jQuery('#prt_promt_load_icon').removeClass('prt-hidden-impo');
		jQuery('.prt-promt-value-found').removeClass('emulateFinish');
		jQuery('.prt-promt-value-found span').text('Übersendung der relevanten Daten Ihrer Immobilie...');
		jQuery('#prt_promt_success_icon').addClass('prt-hidden-impo');
		prtEmulateTimeout = setTimeout(function() {
			jQuery('#prt_promt_load_icon').addClass('prt-hidden-impo');
			jQuery('.prt-promt-value-found').addClass('emulateFinish');
			jQuery('.prt-promt-value-found span').text('Der Wert Ihrer Immobilie wird ermittelt!');
			jQuery('#prt_promt_success_icon').removeClass('prt-hidden-impo');
			prtEmulateTimeout = null;
		}, (Math.floor(Math.random() * (5 - 3 + 1)) + 3) * 1000); //random between 3 and 5 sec.
	}

	// Range Slider - Clean
	jQuery('#prt-root.theme-clean').on('input change', 'input[type="range"]', function (e) {
		var $this = jQuery(this);
		var $step = $this.closest('.step');
		if (!$step.length) return true;
		var $display = jQuery('.range-show-value', $step);
		if ($this[0].hasAttribute('data-values')) {
			var vals = $this.attr('data-values');
			vals = JSON.parse(vals);
			$display.val(vals[e.target.value]);
		}
		else $display.val(e.target.value);
	});

	//Privacy
	jQuery('#prt-root .openPrivacy').on('click', function (e) {
		e.preventDefault();
		jQuery('.dialog').addClass('privacy-active');
		openDialog('Datenschutz', jQuery('.privacy .text').html(), false);
	});

	// Options Box - Theme Clean
	jQuery('#prt-root.theme-clean').on('click', '.option-box', function () {
		var $this = jQuery(this);
		jQuery('.option-box', '#prt-root').removeClass('selected');
		$this.addClass('selected');
	});

	// Options Box - Theme Modern
	jQuery('#prt-root.theme-modern').on('click', '.option-box', function () {
		var $this = jQuery(this);
		jQuery('.option-box', '#prt-root').removeClass('selected');
		$this.addClass('selected');
		if($this.closest('.options').attr('data-isload') == 'true') {
			clickHandlerLoad(this);
		} else {
			clickHandlerNext(this);
		}
	});

	// Confirm Dialog - Callbacks
	jQuery('#prt-root').on('click', '.prt-cancel-cb', function() {
		if (typeof dialogCancelCb === 'function') {
			dialogCancelCb();

			dialogCancelCb = null; dialogNextCb = null;
			closeDialog();
		}
	});
	jQuery('#prt-root').on('click', '.prt-next-cb', function() {
		if (typeof dialogNextCb === 'function') {
			dialogNextCb();

			dialogCancelCb = null; dialogNextCb = null;
			closeDialog();
		}
	});

	//Selectable Buttons
	jQuery('#prt-root').on('click', '.selectable-button li', function () {
		var $this = jQuery(this);
		$this.parent().children().each(function (e) {
			jQuery(this).removeClass('selected');
		});
		$this.addClass('selected');
	});

	jQuery('#prt-root').click(function () {
		closeDialog();
	});

	// Buttons
	jQuery('#prt-root').on('click', '.prt-button', function () {
		var $this = jQuery(this);

		if ($this.hasClass('load')) {
			clickHandlerLoad(this);
		}

		if ($this.hasClass('startEmulateTimeout')) {
			startEmulateTimeout();
		}

		if ($this.hasClass('validateAddress')) {
			validateAddress(this);
			calcProgress();
			return true;
		}

		if ($this.hasClass('finish')) {
			//get last data
			if (!checkForData(this)) {
				openDialog('Fehler', 'Bitte füllen Sie alle erforderlichen Felder aus.');
				return false;
			}

			openLoader();

			jQuery.post(prt_ajax_object.ajax_url,
				Object.assign({ action: 'prt_submit' }, requestData),
				function (data) {

					var isAnError = false;

					switch(data.status) {
						case "success":
							fillResults(data);
							break;
						case "no_rate":
							no_rate_display();
							break;
						case "not_found":
							not_found_display();
							break;
						default:
							isAnError = true;
							break;
					}

					if(!isAnError) {
						closeLoader();
						slideNext();
						calcProgress();
						gaPrtSend(requestData.type + ": Fertig", 100); //GA-Track
						prtSendMails(data._id);
					} else {
						closeLoader();
						showErrorOnLastStep(data.msg);
					}

				}, 'json');
		}

		if ($this.hasClass('next')) {
			clickHandlerNext(this);
		}
		else if ($this.hasClass('prev')) {
			slidePrev();
		}

		calcProgress();
	});

	jQuery(document).ready(function () {
		prtIsMobile = window.matchMedia("only screen and (max-width: 760px)").matches;

		prtSendToStatistics(getCurrentActivity());
		googleMapInit();
		prtRangeSliderInit();

		tippy('.make-a-tippy');
	});
}

prt_js_root();