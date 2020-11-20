import Vuex from 'vuex';
import Vue from 'vue';
import VueCookies from 'vue-cookies';
import Mixin from './mixin.js';

Vue.use(Vuex);
Vue.use(VueCookies);

export default new Vuex.Store({
    state: {
        /* Is application inaccessable (loading) */
        mute: false,

        /* Access token will be retrieved from cookie or using API */
        access_token: null,

		/* Basic information about the user */
		user: {},

        /* Store the inputs for login process */
        input_email: "",
        input_password: "",

        /* Inform the user of authentication status */
        auth_feedback: "Enter your credentials to login or create new account",


    },
    mutations: {

        /**
         * For synchronizing and esthetics purposes we must prevent user input in certain moments
         * this will make application 'mute' to any events by covering the interface
         * with loading screen
         */
        mute(state, value) {
            this.state.mute = value;
        },

        /**
         * Updates state with newly acquired access token
         * Cleans raw inputs from forms
         * Saves token using cookies and puts it inside axios headers for
         * future requests
         */
        updateToken(state, payload) {
            // Update token in the state
            state.access_token = payload.access_token;

            // Update token in the axios default header
            axios.defaults.headers.common["Authorization"] = "Bearer " + state.access_token;

            // Clean the raw inputs after supposed authorization
            this.commit('updateInputValue', {
                name: 'email',
                value: ''
            });
            this.commit('updateInputValue', {
                name: 'password',
                value: ''
            });

            // Save token in cookie
            VueCookies.set('mafiozi.access_token', payload.access_token, 3600);
        },

        /**
         * Used by CustomInput component
         */
        updateInputValue(state, payload) {
            state['input_' + payload.name] = payload.value;
		},
		
        /**
         * Set response message in the authentication box
         */
        updateAuthenticationFeedback(state, payload) {
            state.auth_feedback = payload.message;
		},
		
		updateUserInformation(state, user) {
			state.user = user;
		}
    },
    actions: {
        /**
         * Attempt login process with backend involved, if successful
         * returns an access token used for authorized requests for further application usage
         * 
         * This function will try to inform the user about current status and results
         * using the $store:auth_feedback variables, it is displayed above forms, inside
         * authentication box.
         */
        retrieveToken() {
            // Enter loading state  
            this.commit('mute', true);

            axios.post('/api/auth/login', {
                    email: this.state.input_email,
                    password: this.state.input_password
                })
                .then(response => {
					console.log(response);

                    // For safety measures, proceed only when we are sure that authentication
                    // on the backend happened smoothly      
                    if (response.status === 200) {

						const token = response.data.access_token;
						
						// If invalid or no token came back
						if(!Mixin.tokenExists(token)) {
							// return Unauthorized, which will inform user
							// that the credentials are incorrect
							throw {response: { status: 401 }}
						}

                        // Update state with basic user data
						this.commit('updateUserInformation', response.data.user);
                        this.commit('updateToken', {
                            access_token: token
                        });
                        // Get more info about the user
                        this.dispatch('retrieveUserData');
                    }
                })
                .catch(err => {
                    if (err.response) {
                        /* Get user-friendly error message */
                        let message;
                        switch (err.response.status) {
                            case 422:
                                message = "Invalid email address provided";
                                break;
                            case 401:
                                message = "Invalid password and/or email";
                                break;
                            default:
                                message = "Unknown error occured";
                                console.log(err);
                                break;
                        }

                        /* Update the state and show the users the message */
                        this.commit('updateAuthenticationFeedback', {
                            message
                        })

                    } else if (err.request) {
                        this.commit('updateAuthenticationFeedback', {
                            message: "Request timed out, please try again. If issue persists, come back later!"
                        })
					}
					// Success!?!

                }).finally(_ => {
                    // Exit loading state
                    this.commit('mute', false);
                });
		},
		
		/**
		 * Usually used when loading saved session, reference: Entry at created()
		 */
		retrieveUserData() {
			let cached = VueCookies.get('mafiozi.user-data');
			if(cached) {
				this.commit('updateUserInformation', cached);
			}

			axios.get('api/auth/me').then(response => {
				console.log(response);
				if(response.status === 200) {
					this.commit('updateUserInformation', response.data.user);

					VueCookies.set('mafiozi.user-data', response.data.user);
				}
			});
		},


        /**
         * Will attempt to load previously saved access token if exists,
         * else will safe fail and redirect user to login process
         */
        loadTokenFromCookies() {
            let token = VueCookies.get('mafiozi.access_token');
            if (token !== null && token.length > 4) {
                this.commit('updateToken', {
                    access_token: token
                })
            }
        },


        /**
         * Perform signing out process. Will send request to backend and remove locally stored
         * access token, do nothing if no active token exists
         */
        dropToken() {
            // Make sure that we do have a token
			this.commit('mute', true);
			this.commit('updateUserInformation', {});

            if (this.state.access_token !== null) {
                // Communicate with backend
                axios.post('/api/auth/logout').then(_ => {

                    this.commit("updateToken", {
                        access_token: null
                    });

                }).catch(err => {

                    console.log("Logged out with error, probably invalid token (session expired)");
                    this.commit("updateToken", {
                        access_token: null
                    });

                }).then(_ => {
                    VueCookies.remove('mafiozi.access_token');

                    this.commit('mute', false);
                });

            }
        },
	
		/**
		 * This makes sure that loaded token is valid on the backend side
		 * However if not, it will drop current token
		 */
		validateToken() {
			// Don't proceed if previous session doesn't exist
			if(!Mixin.tokenExists(this.state.access_token)) {
				return;
			}

			// Ask backend if this token is still valid
			axios.post('/api/auth/checkToken')
			.then(response => {
				if(response.data.success !== true) {
					this.dispatch('dropToken');
					console.log("Dropped previous session due to expired token");
				}
			}, err => {
				this.dispatch('dropToken');
				console.log("Dropped previous session due to network or internal errors");
			});

		}

	},

    getters: {

    }
});
