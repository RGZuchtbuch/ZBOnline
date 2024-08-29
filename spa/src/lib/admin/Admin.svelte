<script>
	import {setContext} from 'svelte';
    import { meta, router, Route } from 'tinro';
	import {admin, user} from '../../js/store.js';

	let authenticated = false;
	let authorized = false;
	// setting context from store into context. enables district and breeder handling without role
	setContext( 'district', admin.district );
	setContext( 'breeder', admin.breeder );

    const route = meta();

	function onRoute( route ) {
		authenticated = $user != null;
		if( authenticated ) {
			console.log( 'User', $user );
			authorized = $user.moderator.length > 0;
		} else {
			authorized = false;
			router.goto( '/anmelden' );
		}

	}

	$: onRoute( route );
    console.log( 'Moderator route', route);

</script>

{#if authorized }
	<slot>Hier sollte man nur enden wenn mann angemeldet und Admin ist</slot>
{:else}
	Not authorized !!!!
{/if}


