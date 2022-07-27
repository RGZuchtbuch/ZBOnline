<script>
    import { onMount } from 'svelte';
    import api from "../../js/api.js";
    import BreedSelect from '../select/Breed.svelte';

    export let report;
    export let disabled;

    onMount( () => {
    })

</script>


<div class='flex flex-col my-2'>
    <div>Rasse</div>
    <BreedSelect sectionId={report.sectionId} breedId={report.breedId} colorId={report.colorId}/>
</div>

<style></style>