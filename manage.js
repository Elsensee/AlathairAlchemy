var Alchemy = {
	Effect: function (name, isPositive) {
		this.name = name;
		this.isPositive = isPositive;
	},

	Regency: function (name, effects, price) {
		this.name = name;
		this.effects = effects;
		this.price = price;
	},

	// Add an effect within the popup
	addEffect: function(event) {
		event.preventDefault();

		var $ol = $(this).parent().children('ol');
		if ($ol.children('li').last().children('select').val() == -1) {
			return false;
		}

		Alchemy.createEffectSelect($ol, " ");
	},

	// Click on button to change/display effects
	changeEffects: function(element) {
		var $button = $(element);
		var $trElement = $button.parent().parent();
		var regencyId = $trElement.data("regency");

		if (regencyId > regencies.length) {
			return false;
		}

		var regency = regencies[regencyId];

		var $effectsPopup = $("#effects_popup");
		$effectsPopup.html('');
		$effectsPopup.data('regency', regencyId);
		var $h2 = $(document.createElement('h2'));
		$h2.addClass('bottom_margin').css('text-align', 'center').text(regency.name);
		$effectsPopup.append($h2);

		$effectsPopup.append(document.createElement('ol'));
		var $ol = $effectsPopup.children('ol');

		for (var i = 0; i < regency.effects.length; i++) {
			Alchemy.createEffectSelect($ol, regency.effects[i].name);
		}

		var $addMore = $(document.createElement('a'));
		$addMore.addClass('leftside').attr({'href': '#'}).click(Alchemy.addEffect).text("Effekt hinzufügen");
		var $reset = $(document.createElement('a'));
		$reset.addClass('rightside').attr({'href': '#'}).click(Alchemy.resetEffects).text("Zurücksetzen");
		$effectsPopup.append($addMore, $reset);

		$effectsPopup.append(document.createElement('br'), document.createElement('br'));

		var $buttonCancel = $(document.createElement('button'));
		$buttonCancel.addClass('rightside right_margin effects_popup_close').text("Abbrechen");
		var $buttonOk = $(document.createElement('button'));
		$buttonOk.addClass('rightside effects_popup_close').text("Speichern").click(Alchemy.saveEffects);
		$effectsPopup.append($buttonOk, $buttonCancel);
	},

	// Check if select needs to be removed after select value was changed
	checkSelect: function () {
		var $this = $(this);

		if ($this.val() >= 0) {
			return false;
		}

		var $ol = $this.parent().parent();
		$this.parent().remove();

		if ($ol.children('li').last().children('select').val() >= 0) {
			Alchemy.createEffectSelect($ol, " ");
		}
	},

	// Creates a li element with a select element with options element and a delete button
	// aka everything
	createEffectSelect: function (olElement, selectedEffect) {
		var effectId = -1;

		var $select = $(document.createElement('select'));

		var $nullOption = $(document.createElement('option'));
		$nullOption.attr({'value': -1}).text(" ");
		$select.prepend($nullOption);

		for (var i = 0; i < effects.length; i++) {
			if (selectedEffect == effects[i].name) {
				effectId = i;
			}

			var $option = $(document.createElement('option'));
			$option.attr({'value': i}).text(effects[i].name);
			$select.append($option)
		}
		$select.val(effectId);
		$select.change(Alchemy.checkSelect);

		var $delete = $(document.createElement('a'));
		$delete.addClass('left_margin').attr({'href': '#'}).click(Alchemy.deleteEffect).text("löschen");

		var $li = $(document.createElement('li'));
		$li.append($select, $delete);

		olElement.append($li);

		return effectId;
	},

	// deletes an effect
	deleteEffect: function (event) {
		event.preventDefault();

		$(this).parent().remove();
	},

	// reset the effects within the popup
	resetEffects: function (event) {
		event.preventDefault();

		var regencyId = $("#effects_popup").data('regency');
		var $regencyTr = $("tbody").children("tr").filter(function () {
			return $(this).data("regency") == regencyId;
		});

		var button = $regencyTr.children('td').children('button').get(0);
		Alchemy.changeEffects(button);
	},

	// saves the effects from the popup temporarily (not yet sent to the server)
	saveEffects: function () {
		var $effectsPopup = $("#effects_popup");
		var regencyId = $effectsPopup.data("regency");

		if (regencyId > regencies.length) {
			return false;
		}

		var regency = regencies[regencyId];
		regency.effects = [];

		$effectsPopup.children('ol').children('li').children('select').each(function () {
			var $this = $(this);
			if ($this.val() >= 0) {
				regency.effects.push(effects[$this.val()]);
			}
		});
	},

	// before submitting the form, all values will be added to the POST request
	submitForm: function () {
		$('tr').filter(function () {
			return $(this).data("regency") != undefined;
		}).each(function () {
			var $this = $(this);
			var regencyId = $this.data("regency");
			var regency = regencies[regencyId];

			var regencyEffects = [];
			for (var i = 0; i < effects.length; i++) {
				for (var j = 0; j < regency.effects.length; j++) {
					if (effects[i].name == regency.effects[j].name) {
						regencyEffects.push(i);
					}
				}
			}

			var $hiddenInput = $(document.createElement('input'));
			$hiddenInput.attr({'name': 'regency_effects[]', 'type': 'hidden'});
			$hiddenInput.val(regencyEffects.join());
			$this.append($hiddenInput);
		});
	}
};

$(document).ready(function () {
	// Prepare the form so PHP knows what to do
	$("#javascript_input").remove();
	$("form").submit(Alchemy.submitForm);

	// Prepare the popup
	var $effectsPopup = $('#effects_popup');
	$effectsPopup.popup({ // Initialize popup
		onopen: function () {
			var $ol = $(this).children('ol');
			$ol.css({'min-height': Math.round($ol.height()), 'min-width': Math.round($ol.width())});
		}
	});

	// Columnize the tables
	$("#table_div").columnize({ width: Math.ceil($("#table").width()) + 85, doneFunc: function () {
		// Fix table head
		$('table').prepend($('thead')); // Add thead to all tables

		// After fixing table heads, the table length is a bit weird, now.
		var $tbody = $('tbody');
		var tbodyCount = $tbody.length;

		var elementsPerTable = Math.ceil(regencies.length / tbodyCount);
		var remainingTables = tbodyCount - (regencies.length % tbodyCount);
		$tbody.each(function(i) {
			var $this = $(this);

			var elementsPerThisTable = elementsPerTable;
			if (i > tbodyCount - 1 - remainingTables) {
				elementsPerThisTable--;
			}

			var trCount = $this.children('tr').length;

			if (trCount > elementsPerThisTable) {
				while (trCount > elementsPerThisTable) {
					$tbody.eq(i+1).prepend($this.children('tr').last());
					trCount--;
				}
			} else if (trCount < elementsPerThisTable) {
				while (trCount < elementsPerThisTable) {
					$this.append($tbody.eq(i+1).children('tr').first());
					trCount++;
				}
			}
		});
	}});
});
