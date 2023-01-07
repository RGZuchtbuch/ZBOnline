<script>
    import {Route, meta} from 'tinro';
    import {user} from '../js/store.js';

    console.log( 'Menu', $user );

</script>

<div class='w-48 mt-8 border rounded flex flex-col no-print'>
    <Route path='/leistungen/*'>
        <h3>Leisungen</h3>
        <a href='/leistungen/karte'>GeoInfo</a>
        <a href='/leistungen/trend'>Trends</a>
    </Route>

    {#if $user }
        <Route path='/obmann/*'>
            <h3>Obmann {$user.name}</h3>
            <a href='/obmann/verbaende'>Verbände</a>
            <Route path='/verband/:districtId/*' let:meta>
                <b>Verband name</b>
                <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter'}>Züchter</a>
                <a href={'/obmann/verband/'+meta.params.districtId+'/leistung'}>Leistungen</a>
                <a href={'/obmann/verband/'+meta.params.districtId+'/leistung/edit'}>Eingeben</a>
                <Route path='/zuechter/:breederId/*' let:meta>
                    <b>Breeder name</b>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/mitglied'}>Mitglied</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung'}>Meldungen</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung/0'}>Melden</a>
                </Route>
            </Route>
        </Route>
    {/if}
</div>