<template>
    <div class="auth-wrapper">
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