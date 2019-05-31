<template>
<div class="container">
    <div class="content">
        <div class="top">
            <div class="header">
                <router-link to="/"><img :alt="systemName" class="logo" src="../../../images/logo.svg" /></router-link>
            </div>
        </div>
        <div class="login">
            <a-form @submit="onSubmit" :autoFormCreate="(form) => this.form = form">
                <a-tabs size="large" :tabBarStyle="{textAlign: 'center'}" style="padding: 0 2px;">
                    <a-tab-pane tab="Login" key="1">
                        <a-alert type="error" :closable="true" v-show="error" :message="error" showIcon style="margin-bottom: 24px;" />
                        <a-form-item fieldDecoratorId="sid" :fieldDecoratorOptions="{rules: [{ required: true, message: 'Please enter your SID.', whitespace: true}]}">
                            <a-input size="large" placeholder="SID">
                                <a-icon slot="prefix" type="user" />
                            </a-input>
                        </a-form-item>
                        <a-form-item fieldDecoratorId="password" :fieldDecoratorOptions="{rules: [{ required: true, message: 'Please enter your password.', whitespace: true}]}">
                            <a-input size="large" placeholder="Password" type="password">
                                <a-icon slot="prefix" type="lock" />
                            </a-input>
                        </a-form-item>
                    </a-tab-pane>
                </a-tabs>
                <div>
                    <a-checkbox :defaultChecked="true">Remeber Me</a-checkbox>
                    <a style="float: right">Lost Password</a>
                </div>
                <a-form-item>
                    <a-button :loading="logging" style="width: 100%;margin-top: 24px" size="large" htmlType="submit" type="primary">Login</a-button>
                </a-form-item>
            </a-form>
        </div>
    </div>
    <global-footer :link-list="linkList" :copyright="copyright" />
    <div><img src="../../../images/logo-sustc.svg" style="position: fixed;height: 500px;bottom: -80px;left: -100px;opacity: 0.1;z-index: 100;" alt=""></div>
</div>
</template>

<script>
import AForm from 'ant-design-vue/es/form/Form'
import ATabs from 'ant-design-vue/es/tabs'
import GlobalFooter from '../../layouts/GlobalFooter'
import AFormItem from 'ant-design-vue/es/form/FormItem'
import AInput from 'ant-design-vue/es/input/Input'
import AIcon from 'ant-design-vue/es/icon/icon'
import AInputGroup from 'ant-design-vue/es/input/Group'
import AButton from 'ant-design-vue/es/button/button'
import ACol from 'ant-design-vue/es/grid/Col'
import ARow from 'ant-design-vue/es/grid/Row'
import ACheckbox from 'ant-design-vue/es/checkbox/Checkbox'
import AAlert from 'ant-design-vue/es/alert/index'
import { setCookie } from 'tiny-cookie'

const ATabPane = ATabs.TabPane

export default {
    name: 'Login',
    components: {
        AAlert,
        ACheckbox,
        ACol,
        ARow,
        AButton,
        AInputGroup,
        AIcon,
        AInput,
        AFormItem,
        GlobalFooter,
        ATabPane,
        ATabs,
        AForm
    },
    data() {
        return {
            logging: false,
            error: ''
        }
    },
    computed: {
        systemName() {
            return this.$store.state.setting.systemName
        },
        linkList() {
            return this.$store.state.setting.footerLinks
        },
        copyright() {
            return this.$store.state.setting.copyright
        }
    },
    methods: {
        onSubmit(e) {
            e.preventDefault()
            this.form.validateFields((err, values) => {
                if (!err) {
                    this.logging = true
                    this.$axios.post('/api/auth', {
                        sid: this.form.getFieldValue('sid'),
                        password: this.form.getFieldValue('password')
                    }).then((res) => {
                        this.logging = false
                        const result = res.data
                        if (res.status == 201) {
                            const user = result.meta
                            setCookie('token', result.access_token)
                            this.$axios.defaults.headers.common['Authorization'] = 'Bearer ' + result.access_token
                            this.$store.commit('account/login', result.access_token)
                            this.$router.push('/')
                            var time = new Date()
                            var hour = time.getHours()
                            this.$message.success(hour < 9 ? 'Good Morning' : (hour <= 11 ? 'Good Morning' : (hour <= 13 ? 'Good Afternoon' : (hour <= 20 ? 'Good Afternoon' : 'Good Evening'))) + ', Welcome Back!', 1)
                        }
                    }).catch((error) => {
                        this.logging = false
                        this.error = error.response.data.message
                    })
                }
            })
        }
    }
}
</script>

<style lang="less" scoped>
.container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: auto;
    background: #f0f2f5 url("../../../images/login-bg.svg") no-repeat center 110px;
    background-size: 100%;
    .content {
        padding: 32px 0;
        flex: 1;
        @media (min-width: 768px) {
            padding: 150px 0 24px;
        }
        .top {
            text-align: center;
            .header {
                a {
                    text-decoration: none;
                }
                .logo {
                    width: 360px;
                    vertical-align: top;
                }
            }
        }
        .ant-tabs-bar {
            margin-bottom: 18px;
        }
        .login {
            width: 368px;
            margin: 0 auto;
            margin-top: 10px;
            @media screen and (max-width: 576px) {
                width: 95%;
            }
            @media screen and (max-width: 320px) {
                .captcha-button {
                    font-size: 14px;
                }
            }
            .icon {
                font-size: 24px;
                color: rgba(0, 0, 0, 0.2);
                margin-left: 16px;
                vertical-align: middle;
                cursor: pointer;
                transition: color 0.3s;

                &:hover {
                    color: #12484b;
                }
            }
        }
    }
}
</style>
