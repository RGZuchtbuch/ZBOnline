<script>
    import {onMount} from 'svelte';
    import { app } from '$lib/js/store.svelte.js';
    import { info_icon, test } from '$lib/cmp/snippets.svelte';

    onMount( () => {
        app.menu.title = 'Fehler 404, nicht gefunden';
        app.menu.trail = [
            { name:'Home',      href:'/' },
        ];
        app.menu.options = [
            { name:'Info',       href:'/article' },
            { name:'Verbände',   href:'/district/map' },
            { name:'Standard',   href:'/standard' },
            { name:'Leistungen', href:'/result' },
//			{ name:'Züchter',    href:'/breeder' },
            { name:'Obmann',     href:'/moderator/district' },
            { name:'Admin',      href:'/admin' },
        ];
    })

</script>

<div class=''>
    Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...

</div>

