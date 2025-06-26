<script>
    import { ctx } from '$lib/js/store.svelte.js'

    setHeader();

    function setHeader() {
        const title = null;
        const menu = {
            trail : [
                { name:'Start',  href:'/' },
            ],
            options : [],
        };
        ctx.header.title = title;
        ctx.header.menu  = menu;
    }

</script>

<h2 class='header'>Fehler 404, Seite nicht gefunden</h2>
<div class='text-center'>
    Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...
    <a href='/'>Neustart...</a>

</div>

