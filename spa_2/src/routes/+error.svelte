<script>
    import store from '$lib/js/store.svelte.js';

    setHeader();

    function setHeader() {
        const title = null;
        const menu = {
            trail : [
                { name:'Start',  href:'/' },
            ],
            options : [],
        };
        store.title.update( () => title );
        store.menu.update( () => menu );
    }

</script>

<h2 class='header'>Fehler 404, Seite nicht gefunden</h2>
<div class='text-center'>
    Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...
    <a href='/'>Neustart...</a>

</div>

