// js/services/metadata.js

import { api } from './apiservice.js';

export const Metadata = {
    getAll: () => api.get('/metadata'),
    //Aquí vive la logica específica del negocio
}
