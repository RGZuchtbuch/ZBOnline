import "./app.css";
import {router, Route} from 'tinro';

let App;
if( WORKINPROGRESS ) { // disables site
  //import App from "./WiP.svelte";
  App = (await import( './WiP.svelte')).default;
  console.log( App );

} else {
//  import App from "./App.svelte";
  App = (await import( './App.svelte')).default;
}

router.mode.hash(); // hash method

const app = new App({
  target: document.body, props: {}
});

export default app;
