import Vue from "vue";
import Router from "vue-router";
import PageView from "../layouts/PageView";
import RouteView from "../layouts/RouteView";
import MenuView from "../layouts/MenuView";
import Login from "../pages/login/Login";

import Profile from "../pages/user/Profile";

import Search from "../pages/search/Search"
import SelectedList from "../pages/selection/SelectedList"
import AppointmentDetail from "../pages/appointment/AppointmentDetail"
import CreateAppointment from "../pages/appointment/CreateAppointment"
import SendMessage from "../pages/message/SendMessage"
import Inbox from "../pages/message/Inbox"
import SentBox from "../pages/message/SentBox"
import MessageDetail from "../pages/message/MessageDetail"

import BasicForm from "../pages/form/BasicForm";
import StepForm from "../pages/form/stepForm/StepForm";
import AdvancedForm from "../pages/form/advancedForm/AdvancedForm";
import QueryList from "../pages/list/QueryList";
import StandardList from "../pages/list/StandardList";
import CardList from "../pages/list/CardList";
import SearchLayout from "../pages/search/SearchLayout";
import BasicDetail from "../pages/detail/BasicDetail";
import AdvancedDetail from "../pages/detail/AdvancedDetail";
import SuccessResult from "../pages/result/Success";
import ErrorResult from "../pages/result/Error";
import Error404 from "../pages/exception/404";
import Error403 from "../pages/exception/403";
import Error500 from "../pages/exception/500";
import TaskCard from "../pages/components/TaskCard";
import Palette from "../pages/components/Palette";

import store from "../store";
import { getCookie } from "tiny-cookie";

Vue.use(Router);

console.log(JSON.stringify(store.state.account.user))

var router = new Router({
    mode: "history",
    routes: [
        {
            path: "/login",
            name: "Login",
            meta: {
                requiresNoAuth: true
            },
            component: Login,
            invisible: true
        },
        {
            path: "/",
            name: "Home",
            component: MenuView,
            icon: "none",
            invisible: true,
            children: [
                {
                    path: "/",
                    name: "Search",
                    meta: {
                        requiresAuth: true
                    },
                    component: SearchLayout,
                    icon: "dashboard"
                },
                {
                    path: "/search/:keyword",
                    name: "Search Result",
                    meta: {
                        requiresAuth: true
                    },
                    component: SearchLayout,
                    invisible: true,
                    icon: "dashboard"
                },
                {
                    path: "/profile",
                    name: "Profile",
                    meta: {
                        requiresAuth: true
                    },
                    component: Profile,
                    invisible: true,
                    icon: "none"
                },
                {
                    path: "/courses",
                    name: "Course List",
                    component: SelectedList,
                    icon: "profile",
                    meta: {
                        requiresAuth: true
                    },
                },
                {
                    path: "/form",
                    name: "表单页",
                    component: PageView,
                    icon: "form",
                    invisible: true,
                    children: [
                        {
                            path: "/form/basic",
                            name: "基础表单",
                            component: BasicForm,
                            icon: "none"
                        },
                        {
                            path: "/form/step",
                            name: "分步表单",
                            component: StepForm,
                            icon: "none"
                        },
                        {
                            path: "/form/advanced",
                            name: "高级表单",
                            component: AdvancedForm,
                            icon: "none"
                        }
                    ]
                },
                {
                    path: "/list",
                    name: "列表页",
                    component: PageView,
                    icon: "table",
                    invisible: true,
                    children: [
                        {
                            path: "/list/query",
                            name: "查询表格",
                            component: QueryList,
                            icon: "none"
                        },
                        {
                            path: "/list/primary",
                            name: "标准列表",
                            component: StandardList,
                            icon: "none"
                        },
                        {
                            path: "/list/card",
                            name: "卡片列表",
                            component: CardList,
                            icon: "none"
                        }
                    ]
                },
                {
                    path: "/detail",
                    name: "详情页",
                    icon: "profile",
                    component: RouteView,
                    invisible: true,
                    children: [
                        {
                            path: "/detail/basic",
                            name: "基础详情页",
                            icon: "none",
                            component: BasicDetail
                        },
                        {
                            path: "/detail/advanced",
                            name: "高级详情页",
                            icon: "none",
                            component: AdvancedDetail
                        }
                    ]
                },
                {
                    path: "/exception",
                    name: "异常页",
                    icon: "warning",
                    component: RouteView,
                    invisible: true,
                    children: [
                        {
                            path: "/exception/404",
                            name: "404",
                            icon: "none",
                            component: Error404
                        },
                        {
                            path: "/exception/403",
                            name: "403",
                            icon: "none",
                            component: Error403
                        },
                        {
                            path: "/exception/500",
                            name: "500",
                            icon: "none",
                            component: Error500
                        }
                    ]
                },
                {
                    path: "*",
                    name: "404",
                    icon: "none",
                    component: Error404,
                    invisible: true
                }
            ]
        }
    ]
});

router.beforeEach((to, from, next) => {
    //获取store里面的token
    //let token = store.state.token;
    //判断要去的路由有没有requiresAuth
    if (to.meta.requiresAuth) {
        //需要认证
        store.commit("account/login", getCookie("token"));
        if (store.state.account.token) {
            next();
        } else {
            next({
                path: "/login",
                query: { redirect: to.fullPath } // 将刚刚要去的路由path（却无权限）作为参数，方便登录成功后直接跳转到该路由
            });
        }
    } else if (to.meta.requiresNoAuth) {
        //需要不认证
        if (!store.state.account.token) {
            next();
        } else {
            next({
                path: "/"
            });
        }
    }
    {
        next(); //如果无需token,那么随它去吧
    }
});

export default router;
