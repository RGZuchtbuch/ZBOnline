<script>
	import { getContext } from 'svelte';
    import ModeratorDistricts from '$lib/cmp/moderator/Districts.svelte';
	import {goto} from '$app/navigation';

	let districts = getContext( 'districts' );
	let page      = getContext( 'page' );

    page.title = 'Obmann';
    page.menu = {
		trail : [
			{ name:'Start',    href:'/' },
			{ name:'Obmann',   href:'/moderator' },
		],
	    options : [],
	};

	if( districts.length === 1 ) {
		goto( `/moderator/${districts[0].id}`); // if only one option go there
	}
</script>

<ModeratorDistricts districts={districts}/>



