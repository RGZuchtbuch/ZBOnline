<script>
    import { fade,slide } from 'svelte/transition';

    import { Router, Route, Link, navigate } from 'svelte-navigator';
    import api from '../../../scripts/api.js';
    import Sections from '../components/Sections.svelte';
    import Section from '../components/Section.svelte';
    import Breed from '../components/Breed.svelte';
    import BreedDetail from "../components/BreedDetail.svelte";
    import Breeders from '../components/Breeders.svelte';
    import Color from '../components/Color.svelte';
    import Results from '../components/Results.svelte';

    function onSectionSelect( sectionId ) {
        console.log( 'Standard:onSectionSelect', sectionId);
        navigate( '/standard/section/'+sectionId );
    }

    function onBreedSelect( breedId ) {
        console.log( 'Standard:onBreedSelect', breedId);
        navigate( '/standard/breed/'+breedId );
    }

    function onColorSelect( breedId, colorId ) {
        console.log( 'Standard:onColorSelect', colorId );
        navigate( '/standard/breed/'+breedId+'/color/'+colorId );
    }

    function onDetailsSelect( breedId ) {
        navigate( '/standard/breed/'+breedId );
    }
    function onBreederSelect( breederId ) {
        navigate( '/breeder/'+breederId);
    }
    function onBreedersSelect( breedId ) {
        navigate( '/standard/breed/'+breedId+'/breeders');
    }
    function onResultsSelect( breedId ) {
        navigate( '/standard/breed/'+breedId+'/results');
    }


    console.log( 'run sections', window.location.pathname )

/*
        <Route path='/'> <Sections promise={api.getSections(1)} {onSectionSelect} /> </Route>
        <Route path='section/:sectionId' let:params> <Section id={params.sectionId} {onBreedSelect}/> </Route>
        <Route path='breed/:breedId/*' let:params>
            <Breed id={params.breedId} {onDetailsSelect} {onBreedersSelect} {onResultsSelect}/>
            <Route path='/' let:params> <BreedDetail id={params.breedId} {onColorSelect} /> </Route>
            <Route path='color/:colorId' let:params> <Color id={params.colorId} /> </Route>
            <Route path='breeders' let:params> <Breeders promise={api.getBreedBreeders( params.breedId )} {onBreederSelect}/> </Route>
            <Route path='results' let:params> <Results promise={api.getBreedResults( params.breedId)} /> </Route>
        </Route>
 */
/*
<fieldset class='bordered w-256' transition:slide>
    <legend> Rassegeflügelstandard des BDRG </legend>
    <div class='flex flex-col'>
        Sections
    </div>
</fieldset>
 */
</script>

<fieldset class='bordered w-256' transition:fade|local>
    <legend> Rassegeflügelstandard des BDRG </legend>
    <div class='flex flex-col'>
        <Route path='/'> <Sections promise={api.getSections(1)} {onSectionSelect} /> </Route>
        <Route path='section/:sectionId' let:params> <Section id={params.sectionId} {onBreedSelect}/> </Route>
        <Route path='breed/:breedId/*' let:params>
            <Breed id={params.breedId} {onDetailsSelect} {onBreedersSelect} {onResultsSelect}/>
            <Route path='/' let:params> <BreedDetail id={params.breedId} {onColorSelect} /> </Route>
            <Route path='color/:colorId' let:params> <Color id={params.colorId} /> </Route>
            <Route path='breeders' let:params> <Breeders promise={api.getBreedBreeders( params.breedId )} {onBreederSelect}/> </Route>
            <Route path='results' let:params> <Results promise={api.getBreedResults( params.breedId)} /> </Route>
        </Route>
    </div>
</fieldset>




<style>

</style>