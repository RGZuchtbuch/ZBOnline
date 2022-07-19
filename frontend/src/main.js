import "./app.css";
import {router, Route} from 'tinro';

import App from "./App.svelte";

router.mode.hash(); // hash method

const app = new App({
  target: document.body, props: {}
});

export default app;
