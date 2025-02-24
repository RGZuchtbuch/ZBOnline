<script>
    import { page } from '$lib/js/store.svelte.js';

    page.title = 'Fehler 404, nicht gefunden';
    page.menu.trail = [
        { name:'Home',      href:'/' },
    ];
    page.menu.options = [
        { name:'Info',       href:'/article' },
        { name:'Verbände',   href:'/district/map' },
        { name:'Standard',   href:'/standard' },
        { name:'Leistungen', href:'/result' },
//			{ name:'Züchter',    href:'/breeder' },
        { name:'Obmann',     href:'/moderator/district' },
        { name:'Admin',      href:'/admin' },
    ];

</script>

<div class=''>
    Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...
    <a href='/'>Neustart...</a>

</div>

