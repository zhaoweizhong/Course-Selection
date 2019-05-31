require("./bootstrap");
window.Vue = require("vue");

import App from "./App";
import router from "./router/lazy";
import "ant-design-vue/dist/antd.css";
import Viser from "viser-vue";
import axios from "axios";
import message from "ant-design-vue/es/message";
import "./mock";
import store from "./store";
import {getCookie} from "tiny-cookie";

Vue.prototype.$axios = axios;
Vue.prototype.router = router;
Vue.prototype.$message = message;
Vue.prototype.$store = store;
Vue.config.productionTip = false;
Vue.use(Viser);

axios.interceptors.response.use(
	function (response) {
		return response;
	},
	function (error) {
		console.log("error: " + JSON.stringify(error.response))
		const originalRequest = error.config;
		if (error.response.status === 401 && (error.response.data.message.indexOf('expired')!=-1 || error.response.data.message.indexOf('segments')!=-1) && !originalRequest._retry) {
			originalRequest._retry = true;
			axios.defaults.headers.common["Authorization"] = "Bearer " + getCookie("token");
			return axios.put(Vue.prototype.API_DOMAIN + "/api/auth/current").then(res => {
				store.commit('account/refreshToken', res.data.access_token);
				originalRequest.headers.Authorization =
					"Bearer " + res.data.access_token;
				return axios(originalRequest);
			});
		}
		if ((error.response.status === 500 || error.response.status === 401) && error.response.data.message.indexOf('blacklist')!=-1) {
			store.commit('account/logout')
			Message({
				showClose: true,
				message: '登录失效，请重新登录',
				type: 'error'
			});
			return Promise.reject(error);
		}
		return Promise.reject(error);
	}
);

new Vue({
	el: "#app",
	router,
	store,
	components: {
		App
	},
	template: "<App/>"
});
