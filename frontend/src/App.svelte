<script>
    //  import logo from './assets/svelte.png'
    import {Route, meta} from 'tinro';
    import api from './js/api.js';
    import Breeder from './lib/Breeder.svelte';
    import Login from './lib/Login.svelte';
    import BreederReports from './lib/breeder/Reports.svelte';
    import DistrictBreeders from './lib/district/Breeders.svelte';
    import ModeratorDistricts from './lib/moderator/Districts.svelte';
    import ResultsInput from './lib/result/ResultsInput.svelte';
    import Report from './lib/report/Report.svelte';
    import Results from './lib/Results.svelte';

    import { user } from './js/store.js'

    //const route = meta();

</script>

<div class='w-full h-full border flex flex-col'>
    <img class='absolute w-32' src='../assets/bdrg_logo_r.png'>

    <div class='text-center'>
        Das Rassegeflügel Zuchtbuch. Schönheit und Leisting kombinieren.
    </div>
    <div class='border bg-gray-200 flex flex-row justify-between px-48 border rounded'>
        <div class='flex flex-row gap-x-4'>
            <a href='/'>Das Zuchtbuch</a>
            <a href='/leistungen'>Leistungen</a>
            <a href='/zuechter'>Mein Zuchtbuch</a>
            <a href='/obmann'>Obmann</a>
            <a href='/admin'>Admin</a>
        </div>
        <a href='/#/anmelden'>Anmelden</a>
    </div>

    <div class='grow mx-16 my-2 flex flex-row gap-2 relative min-h-0 '>

        <div class='w-48 mt-24 border rounded flex flex-col '>
            <Route path='/obmann/*'>
                <h3>Obmann</h3>
                <a href='/obmann/verbaende'>Verbände</a>
                <Route path='/verband/:districtId/*' let:meta>
                    <b>Verband name</b>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter'}>Züchter</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/leistung'}>Leistungen</a>
                    <a href={'/obmann/verband/'+meta.params.districtId+'/leistung/eingeben'}>Eingeben</a>
                    <Route path='/zuechter/:breederId/*' let:meta>
                        <b>Breeder name</b>
                        <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/mitglied'}>Mitglied</a>
                        <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldungen'}>Meldungen</a>
                        <a href={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldung/neu'}>Melden</a>
                    </Route>
                </Route>
            </Route>
        </div>

                <Route path='/anmelden'>
                    <Login />
                </Route>
                <Route path='/obmann/*'>
                    <Route path='/' redirect={'/obmann/verbaende'} />

                    <Route path='/verbaende'>

                        <ModeratorDistricts />

                    </Route>

                    <Route path='/verband/:districtId/*' let:meta>

                        <Route path="/" redirect={"/obmann/verband/"+meta.params.districtId+"/zuechter"} />


                        <Route path='/zuechter/*' firstmatch let:meta>
                            <Route path='/' let:meta>
                                <DistrictBreeders districtId={meta.params.districtId}/>

                            </Route>
                            <Route path='/neu' let:meta>
                                Neu
                            </Route>
                            <Route path='/:breederId/*' let:meta>
                                <Route path='/' redirect={'/obmann/verband/'+meta.params.districtId+'/zuechter/'+meta.params.breederId+'/meldungen'} />

                                <Route path='/mitglied' let:meta>
                                    <Breeder promise={api.breeder.get(meta.params.breederId) } />
                                </Route>

                                <Route path='/meldung/*' let:meta>
                                    <Route path='/' let:meta>
                                        <BreederResults promise={api.breeder.results.get(meta.params.breederId) } />
                                    </Route>
                                    <Route path='/neu' let:meta>
                                        <Report promise={api.report.new( meta.params.districtId, meta.params.breederId )} />
                                    </Route>
                                    <Route path='/:reportId' let:meta>
                                        <Report promise={api.report.get(meta.params.reportId)} />
                                    </Route>
                                </Route>

                                <Route path='/meldungen' let:meta>
                                    <BreederReports breederId={meta.params.breederId} />
                                </Route>

                            </Route>
                        </Route>

                        <Route path='/leistung/*' let:meta>
                            results
                            <Route path='/eingeben' let:meta>
                                <ResultsInput promise={api.district.get( meta.params.districtId )} />
                            </Route>
                        </Route>

                    </Route>
                </Route>



        <div class='w-48 border rounded'>info {$user.id}</div>
    </div>

</div>

<style>
    :root {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
        Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

</style>
