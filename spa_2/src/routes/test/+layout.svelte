<script>
    import { onMount } from 'svelte';
    import { cache, state } from '$lib/js/store.svelte.js';

    let { children } = $props(); // get page

    onMount( () => {
        state.trail = [
            { name:'Home',    href:'/' },
            { name:'Obmann',  href:'/moderator' },
        ];
    })
    console.log( 'Test layout' );
</script>

{@render children()}
