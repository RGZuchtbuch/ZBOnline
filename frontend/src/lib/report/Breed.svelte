<script>
    import { onMount } from 'svelte';
    import api from "../../js/api.js";
    import BreedSelect from '../select/Breed.svelte';

    export let breed;
    export let disabled;

    onMount( () => {
    })

</script>


<div class='flex flex-col my-2'>
    <h4>Rasse</h4>
    <BreedSelect bind:sectionId={breed.sectionId} bind:breedId={breed.breedId} bind:colorId={breed.colorId} {disabled}/>
</div>

<style></style>