<script>
    import {Route, meta} from 'tinro';
    import {user} from '../js/store.js';
    import api from '../js/api.js';



</script>

<div class='w-48 mt-8 border rounded flex flex-col no-print'>
    <Route path='/leistungen/*'>
        <h3>Leistungen</h3>
        <a href='/leistungen/'>Grafiken</a>
    </Route>

    {#if $user }
        <Route path='/obmann/*'>
            <h3>Obmann {$user.name}</h3>
            <a href='/obmann/verband'>Verbände</a>
            <Route path='/verband/:districtId/*' let:meta>
                <h4>Verband {#await api.district.get( meta.params.districtId )} ... {:then response} {response.district.short} {/await}</h4>
                <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter'}>Züchter</a>
                <a href={'/obmann/verband/'+meta.params.districtId+'/leistung'}>Leistungen</a>
                <a href={'/obmann/verband/'+meta.params.districtId+'/leistung/edit'}>Eingeben</a>
                <Route path='/zuechter/:breederId/*' let:meta>
                    <h4>Züchter
                        {#await api.breeder.get( meta.params.breederId )} ... {:then response} {response.breeder.name} {/await}
                    </h4>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/daten'}>Daten</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung'}>Meldungen</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung/0'}>Melden</a>
                </Route>
            </Route>
        </Route>

        <Route path='/admin/*' >
            <h3>Admin</h3>
            <a href='/admin/verband'>Verbände</a>
            <a href='/admin/standard'>Standard</a>
            <a href='/admin/seiten'>Seiten</a>
            <Route path='/page/*'>
                <Route path='/'>
                    List
                    <a href='/admin/page/1'>Seite 1</a>
                </Route>
                <Route path='/:pageId' let:meta>
                    Page {meta.params.pageId}
                </Route>
            </Route>
        </Route>
    {/if}

</div>