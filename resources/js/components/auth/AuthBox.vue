<template>
    <div class="auth-wrapper">
        <h3 class="title">Mafiozi</h3>
        <div class="auth-box">
            <header>
                <div class="auth-box__response">
                    <p :class="{'feedback--error': error}">{{ message }}</p>
                </div>
            </header>
            <div class="form-wrapper">
                <LoginForm      v-if="type === 'login'" @show-register="type = 'register'" @show-forgot="type = 'forgot'" />
                <RegisterForm   v-else-if="type === 'register'" @show-login="type = 'login'" @show-forgot="type = 'forgot'"/>
                <ForgotForm     v-else-if="type === 'forgot'" @show-login="type = 'login'" @show-register="type = 'register'"/>
            </div>
        </div>
    </div>
</template>

<script>
// Styles
import '../../styles/auth.scss';

// Components
import LoginForm from './form/LoginForm.vue';
import RegisterForm from './form/RegisterForm.vue';
import ForgotForm from './form/ForgotForm.vue';
// import Logo from '../Logo.vue';

export default {
    name: "AuthBox",
    data() {
        return {
            error: false,
            type: "login"
        }
    },
    components: {
        LoginForm, RegisterForm, ForgotForm
    },
    methods: {
        renderLoginForm() {
            this.type = "login";
        },
        renderRegisterForm() {
            this.type = "register";
        },
        renderForgotForm() {
            this.type = "forgot";
        }
    },
    computed: {
        message() {
            return this.$store.state.auth_feedback;
        }
    }
}
</script>

<style lang="scss" scoped>
.auth-wrapper {
    display: flex;
    flex-flow: column;
    justify-content: center;
}
.title {
    font-family: 'Josefin Sans', 'Nunito', 'Roboto', sans-serif;
    font-size: 4em;
    font-weight: 100;
    letter-spacing: 15px;
    text-align: center;
    text-shadow: 0 0 8px #fff;
    margin-left: 15px; // Align due to font
    margin-bottom: 15px;
}
</style>