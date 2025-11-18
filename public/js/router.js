/*
  Mini router for SIR Acatlán
  Author: César Luna
  Version: 1.0.0
 */

console.log("Welcome to SIR");

const routes = {
    '/': '/components/home.html',
    '/login': '/components/login.html',
    '/report_form': '/components/report_form.html',
    '/search-report': '/components/search_report.html'
};

async function loadRoute(route){
    const path = routes[route] || routes['404'];

    try{
	const response = await fetch(path);
	if(!response.ok) throw new Error('No se pudo cargar la vista');
	const html = await response.text();

	const app = document.getElementById('app');
	app.innerHTML = html;

	initView(route);
    }catch (err) {
	console.error(err);
	document.getElementById('app').innerHTML = `<section><h1>Error</h1><p>No se pudo cargar la vista.</p></section>`;
    }
}

function getCurrentRoute(){
    const hash = window.location.hash || '#/';
    const route = hash.replace('#', '');
    return route;
}

function handleRouteChange(){
    const route = getCurrentRoute();
    loadRoute(route);
}

window.addEventListener('hashchange', handleRouteChange);
window.addEventListener('DOMContentLoaded', handleRouteChange);

function initView(route) {
  if (route === '/login') {
    const form = document.getElementById('login-form');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const data = new FormData(form);
        const email = data.get('email');
        const password = data.get('password');
        console.log('Login con:', email, password);
        // aquí luego conectas con tu API
      });
    }
  }

  // if (route === '/otra-ruta') { ... }
}
