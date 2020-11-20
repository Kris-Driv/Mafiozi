<template>
    <div class="auth-form login-form">
        <form class="auth-form__form" action="#" method="post" @submit.prevent="attemptLogin">
            <CustomInput type="email" label="Username or email" name="email" :focused="true" />
            <CustomInput type="password" label="Password" name="password" />
            <input class="btn" type="submit" value="Sign In">
        </form>
        <div class="auth-options">
            <button class="btn btn--secondary">Register</button>
        </div>
    </div>
</template>

<script>
import CustomInput from './CustomInput.vue';

export default {
    name: "LoginForm",
    components: {
        CustomInput
    },
    methods: {
        attemptLogin() {
            this.$store.dispatch('retrieveToken')
        }
    }
}
</script>