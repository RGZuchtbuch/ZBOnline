<script>
    import {Route, meta, router} from 'tinro';

    const route = meta();

</script>

<div class='flex flex-col no-print'>
    <h3>Obmann</h3>
    <ul>
        <li> <a href={route.match+'/verband'} title='Zur den zu verwalten Verbände'>→ Verbände </a> </li>
    </ul>
    <div class='h-2'></div>
    <ul>
        <li> <a href={route.match+'/grading'} title='Bewertungsrechner'>→ Bewertungsrechner</a> </li>
    </ul>
</div>

<style>

</style>