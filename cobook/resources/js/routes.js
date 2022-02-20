import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
Vue.use(VueRouter);

/* Guest Component */
const Login = () =>
    import(
        "./components/Login.vue" /* webpackChunkName: "resource/js/components/login" */
    );
const Register = () =>
    import(
        "./components/Register.vue" /* webpackChunkName: "resource/js/components/register" */
    );
/* Guest Component */

/* Authenticated Components */
const Dashboard = () =>
    import(
        "./components/Dashboard.vue" /* webpackChunkName: "resource/js/components/dashboard" */
    );

const Workshops = () =>
    import(
        "./components/Workshops.vue" /* webpackChunkName: "resource/js/components/workshops" */
    );
/* Authenticated Components */

var router = new VueRouter({
    mode: "history",
    scrollBehavior: (to, from, savedPosition) => ({ y: 0 }),
    routes: [
        {
            path: "/login",
            name: "login",
            component: Login,
            meta: {
                middleware: "guest",
                title: `Login`,
            },
        },
        {
            path: "/register",
            name: "register",
            component: Register,
            meta: {
                middleware: "guest",
                title: `Register`,
            },
        },
        {
            path: "/",
            name: "dashboard",
            component: Dashboard,
            meta: {
                middleware: "auth",
                title: `Dashboard`,
            },
        },
        {
            path: "/workshops",
            name: "workshops",
            component: Workshops,
            meta: {
                middleware: "auth",
                title: `Workshops`,
            },
        },
    ],
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} - ${process.env.MIX_APP_NAME}`;
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" });
        }
        next();
    } else {
        if (store.state.auth.authenticated) {
            next();
        } else {
            next({ name: "login" });
        }
    }
});

export default router;
