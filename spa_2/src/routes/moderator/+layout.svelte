<script>
	import api from '$lib/js/api.js';
import { app } from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

let { children } = $props();

onMount( () => {
	//const sections = $rootSections;
	getRootSectionsBreeds( app.rootSections, app.standard );
} );

function getRootSectionsBreeds( sections, standard ) {
	for( let section of sections ) {
		const standardSection = standard.sections[ section.id ]; // standard section to get all breeds
		getBreeds( standardSection, section.breeds ); // add to rootsection breeds, recursive
		section.breeds.sort((a, b) => a.name.localeCompare(b.name)); // sort by name
	}
}

function getBreeds( fromSection, breeds ) {
	for( const breed of fromSection.breeds ) {
		breeds.push( breed );
	}
	for( const child of fromSection.children ) {
		getBreeds( child, breeds );
	}
}
</script>

{@render children()}
