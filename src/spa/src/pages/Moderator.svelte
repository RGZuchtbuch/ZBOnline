<script>
    import { meta, Route } from 'tinro';
    import {user} from "../scripts/store";
    import api from '../scripts/api.js';
    import Box from '../components/Box.svelte';

    import Accordion, {AccordionItem} from '../components/Accordion.svelte';
    import Breeder from '../components/Breeder.svelte';
    import Breeders from '../components/Breeders.svelte';
    import Districts from '../components/Districts.svelte';
    import Pair from '../components/Pair.svelte';
    import Pairs from '../components/Pairs.svelte';
    import Results from '../components/Results.svelte';


//    let meta = meta();
    $: console.log( 'Match', meta().match );

    console.log( 'Obmann', $user.name );

    let district = null;
    let breeder = null;

    function getDistrict( id ) {
        api.district.get( id )
            .then( data => {
                district = data;
                console.log( 'Load', district )
            })
    }

</script>

<div class=''>
    <h2 class='text-center'>Obmann {$user.name}</h2>

    <div class='flex flex-row justify-between border gap-2'>
        <div class='w-48 border border-r-gray-400'>

            <Accordion>
                <AccordionItem label={'Obmann menu'} selected>
                    <div><a href={'/#/obmann'}>Verbände</a></div>
                </AccordionItem>

                <Route path='/verband/:districtId/*' let:meta>
                    {#await api.district.get( meta.params.districtId ) then district }
                        <AccordionItem label={'Verband '+district.short}>
                            <div><a href={'/#'+meta.match+'/zuechter'}>Züchter</a></div>
                            <div><a href={'/#'+meta.match+'/stämme'}>Meldungen</a></div>
                            <div><a href={'/#'+meta.match+'/leistungen'}>Leistungen</a></div>
                            <div><a href={'/#'+meta.match+'/berichte'}>Berichte</a></div>
                        </AccordionItem>

                        <Route path='/zuechter/:breederId/*' let:meta>
                            {#await api.breeder.get( meta.params.breederId ) then breeder }
                                <AccordionItem label={'Züchter '+breeder.name}>
                                    <div><a href={'/#'+meta.match+'/'}>Zuchterdaten</a></div>
                                    <div><a href={'/#'+meta.match+'/meldungen'}>Meldungen</a></div>
                                    <div><a href={'/#'+meta.match+'/leistungen'}>Leistungen</a></div>
                                </AccordionItem>
                            {/await}
                        </Route>
                    {/await}
                </Route>
            </Accordion>
        </div>


        <div class='w-192 h-full flex flex-col'>
            <img class='absolute self-end -mt-4 -mr-4 w-16 h-32 border rounded-full -scale-x-100' src='assets/breeder.jpg'>
            <Box legend='Arbeitsfläche'>

                <Route path='/' let:meta>
                    <Districts promise={api.moderator.districts( $user.id )} legend='Verwalte einer deiner Verbände' link='/#/obmann/verband/' />
                </Route>
                <Route path='/verbände' let:meta>
                    {#await api.moderator.districts( $user.id ) then districts}
                        <Districts {districts} legend='Verwalte einer deiner Verbände' link='/#/obmann/verband/' />
                    {/await}
                </Route>

                <Route path='/verband/:districtId/*' let:meta>
                    {#await api.district.get( meta.params.districtId) then district}
                        <Route path='/' let:meta>
                            {#await api.district.getBreeders( district.id ) then breeders}
                                <Breeders breeders={breeders} legend={'Züchter im Verband '+district.name} link={'/#'+meta.match+'/zuechter/'} />
                            {/await}
                        </Route>
                        <Route path='/zuechter' let:meta>
                            {#await api.district.getBreeders( district.id ) then breeders}
                                <Breeders breeders={breeders} legend={'Züchter im Verband '+district.name} link={'/#'+meta.match+'/'} />
                            {/await}
                        </Route>

                        <Route path='/zuechter/:breederId/*' firstmatch let:meta>
                            <Route path='/' let:meta>
                                {#await api.breeder.get( meta.params.breederId ) then breeder}
                                    <Breeder promise={api.breeder.get( meta.params.breederId )} {breeder}/>
                                {/await}
                            </Route>
                            <Route path='/meldung/neu' let:meta>
                                <Pair promise={api.pair.new( meta.params.breederId )} />
                            </Route>
                            <Route path='/meldung/:pairId' let:meta>
                                <Pair promise={api.pair.get( meta.params.pairId )} />
                            </Route>
                            <Route path='/meldungen' let:meta>
                                <Pairs promise={api.breeder.getPairs( meta.params.breederId )} />
                            </Route>
                        </Route>
                    {/await}
                </Route>

            </Box>
        </div>

    </div>
</div>

<style>
    ul.children {
        @apply list-disc;
    }
    ul.children > li {
        @apply ml-4;
    }
</style>