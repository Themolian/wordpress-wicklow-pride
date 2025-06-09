import { exists } from "./main/_exists.helper";
import { ajax_add_to_cart } from "./main/ajax-add-to-cart";

import { cardEnhancement } from "./main/cards";
import { collapsibles } from "./main/collapsibles";
import { disclosureWidget } from "./main/disclosure-widget";
import { formErrorSummary } from "./main/form-error-summary";
import { mapSwitcher } from "./main/map-switcher";
// import { navSingleLevel } from "./main/nav-single-level";
import { navDoubleLevel } from "./main/nav-double-level";
// import { menuToggle } from "./main/menu-toggle";

function domLoadedActions() {
	cardEnhancement();
	collapsibles();
	disclosureWidget();
	formErrorSummary();
	ajax_add_to_cart();
	mapSwitcher();
	// menuToggle();

	/* Create a navSingleLevel object and initiate single-level navigation for a <ul> with the correct data-component attribute */
	const navExampleSingle = document.querySelector(
		'ul[data-component="nav-single"]'
	);

	if (exists(navExampleSingle)) {
		let siteNav = new navSingleLevel(navExampleSingle, {
			breakpoint: 600,
		});
		siteNav.init();
	}

	/* Create a navDoubleLevel object and initiate double-level navigation for a <ul> with the correct data-component attribute */
	const navExampleDouble = document.querySelector(
		'[data-component="nav-double"] ul'
	);

	if (exists(navExampleDouble)) {
		let siteNav = new navDoubleLevel(navExampleDouble, {
			breakpoint: 600,
			submenuDirection: "horizontal",
		});
		siteNav.init();
	}

	/* Create a navDoubleLevel object and initiate double-level navigation for a <ul> with the correct data-component attribute */
	const navDoubleIntro = document.querySelector(
		'ul[data-component="nav-double-intro"]'
	);

	if (exists(navDoubleIntro)) {
		let siteNav = new navDoubleLevel(navDoubleIntro, {
			breakpoint: 600,
			cloneTopLevelLink: false,
			submenuDirection: "horizontal",
			submenuIntro: true,
		});
		siteNav.init();
	}
}

if (document.readyState === "loading") {
	// Loading hasn't finished yet
	document.addEventListener("DOMContentLoaded", domLoadedActions);
} else {
	// `DOMContentLoaded` has already fired
	domLoadedActions();
}
