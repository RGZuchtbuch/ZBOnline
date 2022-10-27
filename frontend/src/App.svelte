<script>
    //  import logo from './assets/svelte.png'
    import {Route} from 'tinro';
    import api from './js/api.js';
    import Breeder from './lib/Breeder.svelte';
    import Breeders from './lib/Breeders.svelte';
    import District from './lib/District.svelte';
    import Districts from './lib/Districts.svelte';
    import ResultsInput from './lib/result/ResultsInput.svelte';
    import Report from './lib/report/Report.svelte';
    import Reports from './lib/Reports.svelte';
    import Results from './lib/Results.svelte';

</script>

<div class='w-full h-full border flex flex-col'>
    <img class='absolute w-32' src='../assets/bdrg_logo_r.png'>

    <div class='text-center'>
        Das Rassegeflügel Zuchtbuch. Schönheit und Leisting kombinieren.
    </div>
    <div class='border bg-gray-200 flex flex-row justify-between px-48 border rounded'>
        <div class='flex flex-row gap-x-4'>
            <a href='/#/obmann'>Das Zuchtbuch</a>
            <a href='/#/obmann'>Leistungen</a>
            <a href='/#/obmann'>Mein Zuchtbuch</a>
            <a href='/#/obmann/verband'>Obmann</a>
            <a href='/#/obmann'>Admin</a>
        </div>
        <div>Anmelden</div>
    </div>

    <div class='mx-16 my-2 flex flex-row gap-2 relative min-h-0 '>

        <div class='w-48 mt-24 border rounded flex flex-col '>
            <Route path='/obmann/*'>
                <h3>Obmann</h3>
                <a href='/obmann/verband'>Verbände</a>
                <Route path='/verband/:districtId/*' let:meta>
                    <b>Verband name</b>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter'}>Züchter</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/leistungen'}>Leistungen</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/leistungen/eingeben'}>Eingeben</a>
                    <Route path='/zuechter/:breederId/*' let:meta>
                        <b>Breeder name</b>
                        <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/mitglied'}>Mitglied</a>
                        <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung'}>Meldungen</a>
                        <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung/neu'}>Melden</a>
                    </Route>
                </Route>
            </Route>
        </div>

        <div class='grow bg-gray-100 overflow-y-scroll border border-black rounded p-4 scrollbar'>
            <Route path='/obmann/*'>
                <Route path='/' redirect={'/obmann/verband'} />

                <Route path='/verband'>
                    <Districts legend={'Verbände für ?'} promise={api.moderator.districts(1)} link={'obmann/verband/'}/>
                </Route>

                <Route path='/verband/:districtId/*' let:meta>

                    <Route path="/" redirect={"/obmann/verband/"+meta.params.districtId+"/zuechter"} />

                    <Route path='/leistungen/*' let:meta>
                        results
                        <ResultsInput {...meta.params} />
                    </Route>

                    <Route path='/zuechter/*' let:meta>
                        <Route path='/' let:meta>
                            <Breeders promise={api.district.breeders.get(meta.params.districtId) } />
                            District Id {meta.params.districtId}, Breeders
                        </Route>
                        <Route path='/:breederId/*' let:meta>
                            <Route path='/' let:meta>
                                <Breeder promise={api.breeder.get(meta.params.breederId) } />
                            </Route>
                            <Route path='/mitglied' let:meta>Mitglied</Route>

                            <Route path='/meldung/*' let:meta>
                                <Route path='/' let:meta>
                                    <Reports promise={api.breeder.reports.get(meta.params.breederId) } />
                                </Route>

                                <Route path='/:reportId' let:meta>
                                    {#if meta.params.reportId === 'neu'}
                                        <Report promise={api.report.new( meta.params.districtId, meta.params.breederId )} />
                                    {:else}
                                        <Report promise={api.report.get(meta.params.reportId)} />
                                    {/if}
                                </Route>
                            </Route>
                        </Route>
                    </Route>
                </Route>
            </Route>
        </div>

        <div class='w-48 border rounded'>info</div>
    </div>

</div>

<style>
    :root {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
        Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

</style>
