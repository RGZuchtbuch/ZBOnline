<script>
    export let src = null;
    export let errorSrc = 'assets/bdrg_zuchtbuch.png';
    export let alt = null;
    export let title = null;

    let classname = '';
    export { classname as class }

    let error = false;

    function onError( e ) {
        console.log( 'Error', e );
        if( ! error ) { // avoid cyclic
            error = true;
            e.target.title = 'Bild fehlt leider noch';
            e.target.src = errorSrc;
        }
    }

//                     <img class='w-full border border-gray-400 rounded' src={'assets/breeds/'+breed.id+'.png'} alt='Bild der Rasse, Hahn und Henne fehlt noch'/>

</script>


<img class={classname} {src} {alt} on:error={onError} title={alt}/>