<script>
    import {Route, meta, router} from 'tinro';
    import {user} from '../js/store.js';
    import api from '../js/api.js';
    import {txt} from '../js/util.js';

    console.log( 'Router', $router );
</script>

<div class='w-48 mt-8 border rounded flex flex-col no-print'>
    <Route path='/leistungen/*'>
        <h3>Leistungen</h3>
        <ul class='list-inside list-disc'>
            <li> <a href='/leistungen/'>Grafiken</a> </li>
        </ul>
    </Route>

    {#if $user }
        <Route path='/obmann/*'>
            <h3>{txt($user.firstname.charAt(0))}. {txt($user.infix)} {txt($user.lastname)}</h3>
            <h3>Obmann</h3>
            <ul class='list-inside list-disc'>
                <li> <a href='/obmann/verband'>Verbände</a> </li>
            </ul>
            <Route path='/verband/:districtId/*' let:meta>
                <h4>Verband {#await api.district.get( meta.params.districtId )} ... {:then response} {response.district.short} {/await}</h4>
                <ul class=''>
                    <li><a href={'/obmann/verband/'+meta.params.districtId+'/zuechter'}>→ Züchter</a></li>
                    <li><a href={'/obmann/verband/'+meta.params.districtId+'/leistung'}>→ Leistungen</a></li>
                    <li><a href={'/obmann/verband/'+meta.params.districtId+'/leistung/edit'}>→ Eingeben</a></li>
                </ul>
                <Route path='/zuechter/:breederId/*' let:meta>
                    <h4>Züchter
                        {#await api.breeder.get( meta.params.breederId )} ... {:then response}
                            {txt(response.breeder.firstname.charAt(0))}. {txt(response.breeder.infix)} {txt(response.breeder.lastname)}
                        {/await}
                    </h4>
                    <ul class='list-inside list-disc'>
                        <li> <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/daten'}>Daten</a> </li>
                        <li> <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung'}>Meldungen</a> </li>
                        <li> <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung/0'}>Melden</a> </li>
                    </ul>
                </Route>
            </Route>
        </Route>

        <Route path='/admin/*' >
            <h3>{txt($user.firstname.charAt(0))}. {txt($user.infix)} {txt($user.lastname)}</h3>
            <h3>Admin</h3>
            <a href='/admin/verband'>→ Verbände</a>
            <a href='/admin/standard'>→ Standard</a>
            <a href='/admin/seiten'>→ Seiten</a>
            <Route path='/verband/:districtId/*' let:meta>
                <h3>Verband</h3>
                <ul>
                    <li><a class='' href={'/admin/verband/'+meta.params.districtId+'/daten'}> → Daten</a></li>
                    <li><a href={'/admin/verband/'+meta.params.districtId+'/zuechter'}>→ Züchter</a></li>
                    <li><a href={'/admin/verband/'+meta.params.districtId+'/leistung'}>→ Leistungen</a></li>
                    <li><a href={'/admin/verband/'+meta.params.districtId+'/leistung/edit'}>→ Eingeben</a></li>
                </ul>

                <Route path='/zuechter/:breederId/*' let:meta>
                    <h3>Breeder</h3>
                    <a href={'/admin/verband/'+meta.params.districtId+'/breeder/'+meta.params.breederId+'/daten'}>→ Daten</a>
                    <a href={'/admin/verband/'+meta.params.districtId+'/breeder/'+meta.params.breederId+'/leistungen'}>→ Leistungen</a>
                    <a href={'/admin/verband/'+meta.params.districtId+'/breeder/'+meta.params.breederId+'/meldungen'}>→ Meldungen</a>
                </Route>

            </Route>
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

<style>

</style>