<script>
    import { page } from '$app/state';
    import { app } from '$lib/js/store.svelte.js';
    import DistrictTree from '$lib/cmp/moderator/DistrictTree.svelte';

    const { data } = $props();
	let moderatables = [];
    const path = page.url.pathname;

    app.title = 'Obmann';
    app.menu.trail = [
        { name:'Start',    href:'/' },
        { name:'Obmann',    href:path },
    ];
    app.menu.options = [];

    console.log( 'Moderator page', path );

</script>

<DistrictTree root={data.districts.root}/>



