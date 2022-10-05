<script>
    //  import logo from './assets/svelte.png'
    import {Route} from 'tinro';
    import api from './js/api.js';
    import Breeder from './lib/Breeder.svelte';
    import Breeders from './lib/Breeders.svelte';
    import District from './lib/District.svelte';
    import Districts from './lib/Districts.svelte';
    import ResultsInputHeader from './lib/ResultsInputHeader.svelte';
    import ResultsInputTable from './lib/ResultsInputTable.svelte';
    import Report from './lib/Report.svelte';
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
            <a href='/#/obmann/verbaende'>Obmann</a>
            <a href='/#/obmann'>Admin</a>
        </div>
        <div>Anmelden</div>
    </div>

    <div class='mx-16 my-2 flex flex-row gap-2 relative min-h-0 '>

        <div class='w-48 mt-24 border rounded flex flex-col '>
            <Route path='/obmann/*'>
                <h3>Obmann</h3>
                <a href='/obmann/verband'>Verband x</a>
                <Route path='/verband/:districtId/*' let:meta>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter'}>Züchter</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/leistungen'}>Leistungen</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/leistungen/eingeben'}>Eingeben</a>
                </Route>
            </Route>
        </div>

        <div class='grow bg-gray-100 overflow-y-scroll border border-black rounded p-4 scrollbar'>
            <Route path='/obmann/*'>
                <Route path='/'>
                    <Districts legend={'Verbände für ?'} promise={api.moderator.districts(1)} link={'obmann/verband/'}/>
                </Route>
                <Route path='/verbaende'>
                    <Districts legend={'Verbände für ?'} promise={api.moderator.districts(1)} link={'obmann/verband/'}/>
                </Route>
                <Route path='/verband/:districtId/*' let:meta>
                    <Route path='/' let:meta>
                        District {meta.params.districtId}
                        <District districtId={meta.params.districtId} />
                    </Route>
test
                    <Route path='/leistungen/*' let:meta>
                        results
                        <ResultsInputHeader {...meta.params} />
                        <Route path='/sparte/:sectionId/jahr/:year/gruppe/:group' let:meta>
                            <ResultsInputTable {...meta.params} />
                        </Route>
                    </Route>

                    <Route path='/zuechter/*' let:meta>
                        <Route path='/' let:meta>
                            <Breeders promise={api.district.getBreeders(meta.params.districtId) } />
                            District Id {meta.params.districtId}, Breeders
                        </Route>
                        <Route path='/:breederId/*' let:meta>
                            <Route path='/' let:meta>
                                <Breeder promise={api.breeder.get(meta.params.breederId) } />
                            </Route>
                            <Route path='/meldung/*' let:meta>
                                <Route path='/' let:meta>
                                    <Reports promise={api.breeder.getReports(meta.params.breederId) } />
                                </Route>
                                <Route path='/:reportId/*' let:meta>
                                    <Route path='/' let:meta>

                                        <Report promise={api.report.get(meta.params.reportId) } />

                                    </Route>
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
