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

<h2 class='m-8 border-header bg-header text-header text-center'>Fehler 404, Seite nicht gefunden</h2>
<div class='m-4 flex flex-col items-center justify-center'>
    <div>Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...</div>
    <a class='m-8 w-24 border-button bg-button text-button p-2' href='/'>Neustart...</a>

</div>

