<script>
    import { getContext } from 'svelte';

    let state = getContext( 'state' )

    page.title = 'Fehler 404, nicht gefunden';
    page.menu = {
        trail: [
            {name: 'Home', href: '/'},
        ],
        options: [],
    }

</script>

<div class=''>
    Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...
    <a href='/'>Neustart...</a>

</div>

