import {createRouter,createWebHistory} from "vue-router";

import TaskList from  "../../companents/TaskList.vue"
import NotFound from "../../companents/NotFoundPage.vue"


const routes = [
    {
        path:'/',
        component:TaskList
    },

    {
        path: '/:pathMatch(.*)*',
        component: NotFound
    },

]
const router = createRouter({
    history : createWebHistory(),
    routes
})

export default router
