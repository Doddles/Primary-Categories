/**
	 * Primary Category JavaScript
	 * Create a dropdown of categories to select one as the primary category
	 * The primary category maps to the `Primary Category` shadow taxonomy
	 * TODO: Respond to new Catgories created on post editing page
	 * TODO: enable wp_localize_scripts for linter
	 * TODO: Add WordPress 5.0 Gutenberg compatibility
*/
var primaryCategory = {

	/**
	 * Initialize the Primary Category functionality - create the new DOM elements and add eventlisteners
	 * @param {String} primaryCatLocal.intoText - into text via `wp_localize_scripts` to enable translation
	 * @param {String} primaryCatLocal.selectText - default select text via `wp_localize_scripts` to enable translation
	 * @param {String} primaryCatLocal.noneText - none message via `wp_localize_scripts` to enable translation
	 */
	init: function init() {
		// TODO: Figure out how to get (non-locally defined) wp_localize_scripts past 10up Plugin Shaffolding!
		var headingText = 'Primary Category: <span></span>';
		var selectText = 'Select Primary Category';

		// select elements for constructing the category list
		var terms = document.querySelectorAll( '#category-all .selectit', i );
		var cats = document.getElementById( 'category-all' );

		// construct our new elements: div:container | p:intro | select:categories
		var container = document.createElement( 'div' );
		var intro = document.createElement( 'p' );
		var catDropdown = document.createElement( 'select' );

		// Change node values if Gutenberg is enabled
		// TODO: Change selectors to make the function WordPress 5.0 compatible
		if ( document.body.className.match( 'gutenberg-editor-page' ) ) {
			terms = document.querySelectorAll( '.editor-post-taxonomies__hierarchical-terms-list .editor-post-taxonomies__hierarchical-terms-choice', i );
			cats = document.querySelectorAll( '.editor-post-taxonomies__hierarchical-terms-list' );

		}

		// add #ID to new primary category container & dropdown
		container.id = 'primary-category-container';

		// add intro text to <p>
		intro.innerHTML = headingText;

		// add event listener to detect changes in primary category value
		primaryCategory.selectListener( container, catDropdown );

		// Loop through the category checkboxes to construct our dropdown
		for ( var i = 0; i < terms.length; ++i ) {
			var term = terms[i].querySelector( 'input' );
			var label = terms[i].innerText;
			var checked = term.checked;
			if ( true === checked ) {
				// if category is allocated to the post (the checkbox is checked), add the category to the dropdown
				primaryCategory.generateOption( catDropdown, term, label, i + 1 );
			}
			// Add event listener to all category checkboxes
			primaryCategory.checkboxListener( container, catDropdown, term, label );
		}

		// create the first option for the primary category dropdown
		primaryCategory.firstOption( catDropdown, selectText );

		// Construct new DOM elements
		cats.prepend( container );
		container.prepend( intro, catDropdown );

		// automatically select current primary category via meta value
		primaryCategory.currentCat( container );
	},

	/**
	 * Initialize the Primary Category functionality
	 * @param {HTMLElement} catDropdown - dynamically generated primary category select dropdown
	 * @param {String} headingText - intro text for primary category dropdown
	 */
	firstOption: function firstOption( catDropdown, selectText ) {
		var firstOption = document.createElement( 'option' );
		firstOption.innerHTML = selectText;
		firstOption.value = '';
		firstOption.setAttribute( 'data-index', 0 );
		catDropdown.prepend( firstOption );
	},

	/**
	 * Initialize the Primary Category functionality
	 * @param {HTMLElement} catDropdown - dynamically generated primary category select dropdown
	 * @param {HTMLElement} term - input (checkbox) element from default category list
	 * @param {String} label - term label
	 * @param {Number} index - dynamically generated primary category select dropdown
	 * @param {String} headingText - intro text for primary category dropdown
	 */
	generateOption: function generateOption( catDropdown, term, label, index ) {
		var option = document.createElement( 'option' );
		option.id = 'primary-' + term.value;
		option.value = term.value;
		option.innerHTML = label;
		option.setAttribute( 'data-index', index );
		catDropdown.appendChild( option );
	},

	/**
	 * Initialize the Primary Category functionality
	 * @param {HTMLElement} container - container for new primary category section added to the DOM
	 */
	currentCat: function currentCat( container ) {
		var meta = document.getElementById( 'primary-category-meta' );
		if ( meta ) {
			if ( document.getElementById( 'primary-' + meta.value ) ) {
				var current = document.getElementById( 'primary-' + meta.value );
				document.querySelector( '#primary-category-container select [value="' + meta.value + '"]' ).selected = true;
				container.querySelector( 'p span' ).innerHTML = current.text;
			} else {
				// TODO: make translatable string for `none` message
				container.querySelector( 'p span' ).innerHTML = '<i>none</i>';
				document.querySelector( '#primary-category-container select [value=""]' ).selected = true;
			}
		}
	},

	/**
	 * Initialize the Primary Category functionality
	 * @param {HTMLElement} container - container for new primary category section added to the DOM
	 * @param {HTMLElement} catDropdown - dynamically generated primary category select dropdown
	 * @param {HTMLElement} term - input ( checkbox ) element from default category list
	 * @param {String} label - term label
	 */
	checkboxListener: function checkboxListener( container, catDropdown, term, label ) {
		term.addEventListener( 'click', function (  ) {
			var checked = this.checked;
			if ( true === checked ) {
				if ( !document.getElementById( 'primary-' + term.value ) ) {
					primaryCategory.generateOption( catDropdown, term, label );
					// check if the visibility status of the dropdown needs to change
					primaryCategory.dropDownVisibility( container, catDropdown );
				}
			} else {
				if ( document.getElementById( 'primary-' + term.value ) ) {
					document.getElementById( 'primary-' + term.value ).remove(  );
					// check if the visibility status of the dropdown needs to change
					primaryCategory.dropDownVisibility( container, catDropdown );
					// check if this is our metabox value - if so, remove it
					if ( document.getElementById( 'primary-category-meta' ).value === term.value ) {
						document.getElementById( 'primary-category-meta' ).value = '';
					}
					// reset current category
					primaryCategory.currentCat( container, catDropdown );
				}
			}
		} );
	},

	/**
	 * Initialize the Primary Category functionality
	 * @param {HTMLElement} container - container for new primary category section added to the DOM
	 * @param {HTMLElement} catDropdown - dynamically generated primary category select dropdown
	 */
	dropDownVisibility: function dropDownVisibility( container, catDropdown ) {
		if ( 2 > catDropdown.length ) {
			container.classList.add( 'hidden' );
		} else {
			container.classList.remove( 'hidden' );
		}
	},

	/**
	 * Initialize the Primary Category functionality
	 * @param {HTMLElement} container - container for new primary category section added to the DOM
	 * @param {HTMLElement} catDropdown - dynamically generated primary category select dropdown
	 */
	selectListener: function selectListener( container, catDropdown ) {

		catDropdown.addEventListener( 'change', function () {
			document.getElementById( 'primary-category-meta' ).value = catDropdown.value;
			// reset current category
			primaryCategory.currentCat( container, catDropdown );
		} );
	}
};

document.addEventListener( 'DOMContentLoaded', function () {
	primaryCategory.init();
} );
