let mapSwitcher = function () {
	console.log("Loaded map switcher code");

	let eventButtons = document.querySelectorAll(".festival-event__button");
	let eventCards = document.querySelectorAll(".festival-events__details");

	eventButtons.forEach((eventBtn) => {
		eventBtn.addEventListener("click", (e) => {
			let btn = e.target;
			let btnId = btn.dataset.eventId;

			eventCards.forEach((eventCard) => {
				let isCurrentEvent = eventCard.dataset.currentEvent;
				let cardId = eventCard.dataset.eventId;
				if (cardId == btnId) {
					let currentEvent = document.querySelector(
						'[data-current-event="1"]'
					);
					if (eventCard.dataset.currentEvent != 1) {
						currentEvent.dataset.currentEvent = 0;
						eventCard.dataset.currentEvent = 1;
					}
				}
			});

			// if (eventCard.dataset.currentEvent != 1) {
			// 	currentEvent.dataset.currentEvent = 0;
			// 	eventCard.dataset.currentEvent = 1;
			// }
		});
	});
};
export { mapSwitcher };
