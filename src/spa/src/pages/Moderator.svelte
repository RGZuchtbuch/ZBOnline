<script>
    import { active, meta, router, Route } from 'tinro';
    import Box from '../components/Box.svelte';
    import {user} from "../scripts/store";
    import api from '../scripts/api.js';

    import SelectTreeNode from '../components/SelectTreeNode.svelte';

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( currentUser );
    });

    console.log( 'Obmann' );
    let districtsPromise = api.moderator.districts( currentUser.id );


    let root = {
        name:'Root', children:[
            {name:'A'},
            {name:'B', children:[
                {name:'Ba'},
                {name:'Bb', children:[
                    {name:'Bb1'},
                    {name:'Bb2'},
                    {name:'Bb3'}
                ]}
            ]}
        ]
    }

    function onDistrictSelect( district ) {
        console.log( 'District selected', district );
        router.goto( '/obmann/verband/'+district.id );
    }

</script>

<div class='w-192'>
    <h2 class='text-center'>Obmann {currentUser.name}</h2>

    <div class='flex flex-row justify-between'>
        <div class='w-48'>
            <Box legend='Obmann menu'>
                <Route path='/verband/:districtId/*' let:meta>
                    <ul>
                        <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Züchter</a></li>
                        <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/meldungen'}>Meldungen</a></li>
                        <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Leistungen</a></li>
                        <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Berichte</a></li>
                    </ul>
                </Route>
            </Box>
        </div>

        <div class='w-128'>

            <Box legend='Obmann'>
                <div class='flex flex-col'>

                    <Route path='/'>
                        {#await api.moderator.districts( currentUser.id )}
                            <div>Obmann bezirke werden geladen....</div>
                        {:then districts}
                            <div>Obmann bezirke</div>
                            <SelectTreeNode children={districts} onSelect={onDistrictSelect}/>
                        {/await}

                    </Route>

                    <Route path='/verband/:districtId/*' let:meta>
                        {#await api.district.get( meta.params.districtId )}
                            <div>Verbands daten werden geladen....</div>
                        {:then district}
                            <div>Verband {district.name}</div>

                            <Route path='/'>
                                <ul>
                                    <li><a href={'/#/obmann/verband/'+district.id+'/zuechter'}>Züchter</a></li>
                                    <li><a href={'/#/obmann/verband/'+district.id+'/meldungen'}>Meldungen</a></li>
                                    <li><a href={'/#/obmann/verband/'+district.id+'/leistungen'}>Leistungen</a></li>
                                    <li><a href={'/#/obmann/verband/'+district.id+'/berichte'}>Berichte</a></li>
                                </ul>
                            </Route>

                            <Route path='/zuechter/*'>
                                <Route path='/'>
                                    <div>Zuechter {district.id} +</div>
                                    {#await api.district.breeders( district.id) }
                                        Züchter werden geladen....
                                    {:then breeders}
                                        Got ({breeders.length})
                                        <ul>
                                            {#each breeders as breeder}
                                                <li><a href={'/#/obmann/verband/'+district.id+'/zuechter/'+breeder.id}>{breeder.name}</a></li>
                                            {/each}
                                        </ul>
                                    {/await}
                                </Route>

                                <Route path='/:breederId/*' let:meta>
                                    <div>Zuechter {meta.params.breederId} +</div>
                                    {#await api.breeder.get( meta.params.breederId ) }
                                        Züchter wird geladen....
                                    {:then breeder}
                                        <div>{breeder.name} von {breeder.club.name}</div>
                                        <Route path='/'>
                                            {#await api.breeder.getResults( breeder.id )}
                                                Leistungen werden geladen.....
                                            {:then results}
                                                <div>
                                                    {#each results as result}
                                                        <div>
                                                            → {result.count}, {result.year}, {result.breedName}, {result.colorName}
                                                        </div>
                                                    {/each}
                                                </div>
                                            {/await}
                                        </Route>
                                    {/await}
                                </Route>
                            </Route>

                            <Route path='/leistungen'>Leistungen</Route>
                            <Route path='/berichte'>Berichte</Route>

                        {/await}
                    </Route>
                </div>
            </Box>
        </div>

        <div></div>
    </div>
</div>
