import Vue from 'vue'
import VueRouter from 'vue-router'

import prehomeCpnt from './components/prehomeCpnt.vue'
import inscriptionCpnt from './components/inscriptionCpnt.vue'

Vue.use(VueRouter)

const router = new VueRouter({
	routes: [
		{
			path: '/',
			component: prehomeCpnt
		},
		{
			path: '/inscription',
			component: inscriptionCpnt
		}
	]
});

export default router
