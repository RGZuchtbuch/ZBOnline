<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let id;
    export let onDetailsSelect = ()=>{};
    export let onBreedersSelect = ()=>{};
    export let onResultsSelect = ()=>{};

    function onDetails( event ) { onDetailsSelect( id ) }
    function onBreeders( event ) { onBreedersSelect( id ) }
    function onResults( event ) { onResultsSelect( id ) }

    let pair;
    let promise = api.getPair( id ).then( ( data ) => {
        console.log( 'Data', data );
        pair = data;
    });

    console.log( 'run pair' );
</script>


{#await promise}
    loading {id}
{:then paipr}
    <fieldset class='bordered'>
        <legend> Stamm {pair.code} </legend>
        <div class='w-256 flex flex-col'>
            <form>
                Jahr {pair.year} - ZüchterNr {pair.breeder.number} - Code <input bind:value={pair.code} >
                <fieldset class='bordered'>
                    <legend> Züchter </legend>
                    {pair.breeder.number} - {pair.breeder.name}
                </fieldset>

                <fieldset class='bordered'>
                    <legend> Abstammung </legend>
                    <div>Section (treeselect)</div>
                    <div>Breed</div>
                    <div>Color</div>
                    <div>Sire aus Stamm yyyy.znr.code : leistungen 160, 0.8, 94</div>
                    <div>Dame aus Stamm yyyy.znr.code : leistungen 160, 0.8, 94</div>
                    <div>Dame aus Stamm yyyy.znr.code : leistungen 160, 0.8, 94</div>
                    <div>Dame aus Stamm yyyy.znr.code : leistungen 160, 0.8, 94</div>
                </fieldset>

                <fieldset class='bordered'>
                    <legend> Legeleistung </legend>
                    <div>Sammeln ab <input value={'01-02-2022'}> bis eins. <input value={'01-03-2022'}> = {29} Tage</div>
                </fieldset>

                <fieldset class='bordered'>
                    <legend> Brutleistung </legend>
                    <div>Eigelegt 01.03.2022   Eier 100, Befruchtet 90, Geschlüpft 80</div>
                    <div>Eigelegt 01.04.2022   Eier 100, Befruchtet 90, Geschlüpft 80</div>
                </fieldset>

                <fieldset class='bordered'>
                    <legend> Schauleistung </legend>
                    89 x 1, 90 x 2, 91 x 3, 92x2, 93x1, 94x8, 95x3, 96x2, 97x0
                </fieldset>
            </form>
            <div>Leistungen Lege 3*160, Brut 200/180/160, Schau 22*94.1</div>
        </div>
    </fieldset>


{:catch error}
    oeps
{/await}


<style>

</style>