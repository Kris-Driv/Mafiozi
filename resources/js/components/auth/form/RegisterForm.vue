<template>
    <div class="auth-form register-form">        
        <form class="auth-form__form" action="#" method="post" @submit.prevent="attemptRegister">
            <CustomInput ref="email" type="email" label="Email" name="email" :focused="true" />
            <CustomInput ref="username" type="text" label="Username" name="username" />
            <CustomInput ref="password" type="password" label="Password" name="password" />
            <CustomInput ref="repeat_password" type="password" label="Repeat Password" name="repeat_password" />
            <input class="btn" type="submit" value="Register New Account">
        </form>
        <div class="auth-options">
            <button class="btn btn--secondary" @click="$emit('show-login')" >Sign In</button>
        </div>
    </div>
</template>

<script>
import CustomInput from './CustomInput.vue';

export default {
    name: "RegisterForm",
    components: {
        CustomInput
    },
    computed: {
        username() {
            return this.$refs.username.value;
        },
        email() {
            return this.$refs.email.value;
        },
        password() {
            return this.$refs.password.value;
        },
        password_repeat() {
            return this.$refs.repeat_password.value;
        }
    },
    methods: {
        
        /**
         * Make sure that we have some proper data to work with and 
         * hopefully to send as well.
         */
        attemptRegister() {
            let pass_one = this.password;
            let pass_two = this.password_repeat;
            let username = this.username;
            let email    = this.email;

            if(pass_one.length <= 1 || pass_two.length <= 1 || username.length <= 1 || email.length <= 1) {
                this.$store.commit('updateAuthenticationFeedback', {
                    message: "Check your inputs and try again"
                });
                return;
            }

            // Validate if password is long enough
            if(pass_one.length < 8) {
                this.$store.commit('updateAuthenticationFeedback', {
                    message: "Password must be atleast 8 characters long"
                });
                return;
            }

            // Check if same password was typed twice
            if(pass_one != pass_two) {
                this.$store.commit('updateAuthenticationFeedback', {
                    message: "Passwords did not match, checks your inputs and try again"
                });
                return;
            }

            // Check if username was given
            if(username.length <= 0) {
                this.$store.commit('updateAuthenticationFeedback', {
                    message: "Please enter your desired username"
                });
                return;
            }

            // Validate the length of the username
            if(username.length < 6) {
                this.$store.commit('updateAuthenticationFeedback', {
                    message: "Username must be at least 6 characters long"
                });
                return;
            }

            // All set! Let's inform the user and start the registration process
            this.$store.commit('updateAuthenticationFeedback', {
                message: "Registering ..."
            });

            this.$store.commit('mute', true);
            
            this.sendRegistrationForm().finally(_ => {
                this.$store.commit('mute', false);
            });

        },
        /**
         * Send registration form, will attempt to register user
         */
        sendRegistrationForm() {
            return axios.post('/api/auth/register', {
                email: this.email,
                password: this.password,
                password_confirmation: this.password_repeat,
                username: this.username
            }).then(response => {
                console.log(response);
                if(response.status === 201) {
                    // User registered successfully, redirect to login screen
                    this.$emit('show-login');
                    this.$nextTick(_ => {
                        this.$store.commit('updateAuthenticationFeedback', {
                            message: "Your account has been registered. Use Your credentials to login!"
                        });
                    });
                }
            }).catch(err => {
                console.log(err.response);
                if(err.response.status === 400) {
                    let feedback = "";
                    let data = err.response.data;

                    // I could explicitly point to the inputs
                    // and show the errors accordingly, but for now
                    // this shall work
                    if(data.email) feedback += data.email + " ";
                    if(data.password) feedback += data.password + " ";
                    if(data.username) feedback += data.username + " ";

                    feedback.trim();

                    this.$store.commit('updateAuthenticationFeedback', {
                        message: feedback
                    });

                } else if (err.response.status === 500) {
                    this.$store.commit({message: 'Internal server occured, please come back later'});
                } else {
                    this.$store.commit({message: 'Unknown error has occured, please come back later'});
                }
            })
        }
    },
    mounted() {
        this.$store.commit('updateAuthenticationFeedback', {
            message: "Fill the form and proceed with registration"
        });
    }
}
</script>