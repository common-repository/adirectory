'use strict';

window.addEventListener('load', windowLoaded, false);

function windowLoaded() {
	var tabs = document.querySelectorAll('.adqs-log-regi-tabs')[0],
		login = document.querySelectorAll("a[data-content='login']")[0],
		signup = document.querySelectorAll("a[data-content='signup']")[0],
		tabContentWrapper = document.querySelectorAll(
			'ul.adqs-log-regi-tabs-content'
		)[0],
		currentContent = document.querySelectorAll('li.selected')[0];

	login.addEventListener('click', clicked, false);
	signup.addEventListener('click', clicked, false);

	function clicked(event) {
		event.preventDefault();

		var selectedItem = event.currentTarget;
		if (selectedItem.className === 'selected') {
			// ...
		} else {
			var selectedTab = selectedItem.getAttribute('data-content'),
				selectedContent = document.querySelectorAll(
					"li[data-content='" + selectedTab + "']"
				)[0];

			if (selectedItem == login) {
				signup.className = '';
				login.className = 'selected';
			} else {
				login.className = '';
				signup.className = 'selected';
			}

			currentContent.className = '';
			currentContent = selectedContent;
			selectedContent.className = 'selected';

			document.querySelector('.adqs-login-regi-error').innerHTML = '';
		}
	}

	const pass_icons = document.querySelectorAll('.adqs-input-wrapper .icon');

	pass_icons.forEach((icon) => {
		icon.addEventListener('click', (e) => {
			e.preventDefault();
			e.currentTarget.classList.toggle('clicked');
			const input = e.currentTarget.parentNode.querySelector('input');
			if (input) {
				const type = input.getAttribute('type');

				if (type === 'password') {
					input.setAttribute('type', 'text');
				} else {
					input.setAttribute('type', 'password');
				}
			}
		});
	});
}

// _is_featured
