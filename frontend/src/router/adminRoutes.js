import AdminHome from "@/views/admin/AdminHome";
import AdminNewScorecard from "@/views/admin/AdminNewScorecard";
import store from "@/store/store";
import AdminManageScorecard from "@/views/admin/AdminManageScorecard";

const checkLoggedIn = (to, from, next) => {
    let user = store.state.user;
    if (user.token !== null && user.expiresIn !== null && user.receiveTime !== null) {
        // All data is present so just assume logged in for no
        if (Date.now() < (user.receiveTime + user.expiresIn)) {
            next();
        } else {
            next({name: 'Login'});
        }
    } else {
        next({name: 'Login'});
    }
}

export default [
    {
        path: '/admin',
        name: 'AdminHome',
        component: AdminHome,
        meta: {
            title: 'Admin:Home'
        },
        beforeEnter: checkLoggedIn
    },
    {
        path: '/admin/scorecards/new',
        name: 'AdminNewScorecard',
        component: AdminNewScorecard,
        meta: {
            title: 'Admin:New Scorecard'
        },
        beforeEnter: checkLoggedIn
    },
    {
        path: '/admin/scorecards/manage',
        name: 'AdminManageScorecard',
        component: AdminManageScorecard,
        meta: {
            title: 'Admin:Manage Scorecards'
        },
        beforeEnter: checkLoggedIn
    }
]