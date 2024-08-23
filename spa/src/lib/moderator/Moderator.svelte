<script>
    import { meta, router, Route } from 'tinro';
    import { onMount, setContext } from 'svelte';
    import { moderator, user } from '../../js/store.js';

	// setting context from store into context. enables district and breeder handling without role
	setContext( 'district', moderator.district );
	setContext( 'breeder', moderator.breeder );

	let authenticated = false;
	let authorized = false;

    const route = meta();

	function onRoute( route ) {
		authenticated = $user != null;
		if( authenticated ) {
			authorized = $user.moderator.length > 0;
		} else {
			authorized = false;
			router.goto( '/anmelden' );
		}

	}

	$: onRoute( route );

</script>

{#if authorized }
	<slot>Hier sollte man nur enden wenn mann angemeldet und Obmann ist</slot>
{:else}
	Not authorized !!!!
{/if}
