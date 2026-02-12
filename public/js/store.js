// js/store.js

import { Metadata } from './services/metadata.js';

export const Store = {

    state: {
	metadata: null,
	isFetched: false
    },

    async fetchMetadata() {
	if(state.isLoaded === true) return this.state.metadata;
	
	try {	    
	    const data = await Metadata.getAll();
	    this.state.isLoaded = true;
	    this.state.metadata = data;
	    return data;
	} catch (e) {
	    console.error("Something bad happened...", e);
	    throw e;
	}
    }

}
