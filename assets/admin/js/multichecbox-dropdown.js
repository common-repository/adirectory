(function ($) {
	const CheckboxDropdown = function (el) {
		const _this = this;
		this.isOpen = false;
		this.areAllChecked = false;
		this.$el = $(el);
		this.$label = this.$el.find('.qsd-dropdown-label');
		this.$checkAll = this.$el.find('[data-toggle="check-all"]');
		this.$inputs = this.$el.find('.item-check[type="checkbox"]');

		this.onCheckBox();

		this.$label.on('click', function (e) {
			_this.toggleOpen();
		});

		this.$checkAll.on('change', function (e) {
			_this.onCheckAll();
		});

		this.$inputs.on('change', function (e) {
			_this.onCheckBox();
		});
	};

	CheckboxDropdown.prototype.onCheckBox = function () {
		this.updateStatus();
	};

	CheckboxDropdown.prototype.updateStatus = function () {
		const checked = this.$el.find('.item-check:checked');

		this.areAllChecked = false;

		if (checked.length <= 0) {
			this.$label.html('Select Options');
			this.$checkAll.find('[type="checkbox"]').prop('checked', false);
		} else if (checked.length === 1) {
			this.$label.html(checked.parent('label').text());
			this.$checkAll.find('[type="checkbox"]').prop('checked', false);
		} else if (checked.length === this.$inputs.length) {
			this.$label.html('All Selected');
			this.$checkAll.find('[type="checkbox"]').prop('checked', true);
			this.areAllChecked = true;
		} else {
			this.$label.html(checked.length + ' Selected');
			this.$checkAll.find('[type="checkbox"]').prop('checked', false);
		}
	};

	CheckboxDropdown.prototype.onCheckAll = function (checkAll) {
		if (!this.areAllChecked || checkAll) {
			this.areAllChecked = true;
			this.$inputs.prop('checked', true);
		} else {
			this.areAllChecked = false;
			this.$inputs.prop('checked', false);
		}

		this.updateStatus();
	};

	CheckboxDropdown.prototype.toggleOpen = function (forceOpen) {
		const _this = this;

		if (!this.isOpen || forceOpen) {
			this.isOpen = true;
			this.$el.addClass('on');
			$(document).on('click', function (e) {
				if (!$(e.target).closest('[data-control]').length) {
					_this.toggleOpen();
				}
			});
		} else {
			this.isOpen = false;
			this.$el.removeClass('on');
			$(document).off('click');
		}
	};

	const checkboxesDropdowns = document.querySelectorAll(
		'[data-control="checkbox-dropdown"]'
	);
	for (let i = 0, length = checkboxesDropdowns.length; i < length; i++) {
		new CheckboxDropdown(checkboxesDropdowns[i]);
	}
})(jQuery);
