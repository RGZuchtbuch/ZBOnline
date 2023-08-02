<script>
    import {Route, meta, router} from 'tinro';

    const route = meta();
</script>

<div class='flex flex-col'>
    <h3>Admin</h3>
    <ul>
        <li> <a href={route.match+'/verband'} title='Zur den zu verwalten Verbände'>→ Verbände </a> </li>
        <li> <a href={route.match+'/standard'} title='Zur Standardverwaltung'>→ Standard </a> </li>
        <li> <a href={route.match+'/seite'} title='Zur Seitenverwaltung'>→ Seiten </a> </li>
        <li> <a href={route.match+'/log'} title='Zur Logbook'>→ Log </a> </li>
    </ul>
</div>

<style>

</style>