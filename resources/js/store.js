import Vuex from 'vuex';
import Vue from 'vue';
import VueCookies from 'vue-cookies';
import Mixin from './mixin.js';

Vue.use(Vuex);
Vue.use(VueCookies);

const DEFAULT_TRANSITION = "slide";

export default new Vuex.Store({
    state: {
        /* Is application inaccessable (loading) 
         *
         * Each component may now implement this feature itself by adding :class="{muted}" to root element
         * And use following methods: mute() & unmute()
         * 
         * This, however, will disable entire application, so this stays as global switch
         */
        mute: false,

        /* Access token will be retrieved from cookie or using API */
        access_token: null,

        /* Validating token */
        validating_token: false,

		/* Basic information about the user */
		user: {},

        /* Store the inputs for login process */
        input_email: "",
        input_password: "",
        /* Store the inputs for register process, inputs above are shared */
        input_username: "",
        input_repeat_password: "",

        /* Inform the user of authentication status */
        auth_feedback: "",

        /* Player stats, such as money, energy, health etc */
        stats: {},

        /* Lock state, when synchronizing player data */
        synchronizing: false,

        /* Job list */
        jobs: null,

        /* View transition effect */
        transitionName: DEFAULT_TRANSITION,

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
         * 
         * see dropToken for the opposite action
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
            
            // If we acquire stats from the params, then parse them
            // into more easily usable parts
            let stats = user.stats;
            if(!stats) {
                console.log("Stats were not updated");
                return;
            }

            this.commit('updateStats', Mixin.methods.parseStats(stats));
        },
        
        /**
         * Set parsed stats object
         */
        updateStats(state, stats) {
            state.stats = stats;
        },

        /**
         * Set available jobs
         */
        updateJobList(state, jobs) {
            state.jobs = jobs;
        },

        /**
         * Sets new values to stats
         */
        updateStatsValues(state, values) {
            let value;
            for(let property in values) {
                value = values[property];

                switch(property) {
                    case "xp":
                        // Level up?
                    default: break;
                }
                state.stats[property].value = values[property];
            }
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
                    // For safety measures, proceed only when we are sure that authentication
                    // on the backend happened smoothly      
                    if (response.status === 200) {

						const token = response.data.access_token;
						
						// If invalid or no token came back
						if(!Mixin.methods.tokenExists(token)) {
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
                                message = "Incorrect password and/or email";
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
            if(!this.state.synchronizing) {
                // Lock
                this.state.synchronizing = true;

                try {
                    let cached = VueCookies.get('mafiozi.user-data');
                    if(cached) {
                        this.commit('updateUserInformation', cached);
                    }
                } catch (err) {
                    VueCookies.remove('mafiozi.user-data');
                    console.log("Cached user data discarded due to: causing errors");
                }

                axios.get('api/auth/me').then(response => {
                    if(response && response.status === 200) {
                        this.commit('updateUserInformation', response.data.user);

                        VueCookies.set('mafiozi.user-data', response.data.user);
                    }
                }).catch(err => {
                    console.log("Error while synchronozing user data", err);
                }).finally(_ => {

                    // Unlock
                    this.state.synchronizing = false;

                });
            } else {
                console.log("Request already on its way!");
            }
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
        dropToken(force = false) {
            // Make sure that we do have a token
			this.commit('mute', true);
			this.commit('updateUserInformation', {});

            if (this.state.access_token !== null && this.state.validating_token === false && !force) {
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

                }).finally(_ => {
                    VueCookies.remove('mafiozi.access_token');

                    this.commit('mute', false);
                });

            } else {
                // Simply drop user data forcefully
                this.commit("updateToken", {
                    access_token: null
                });
                VueCookies.remove('mafiozi.access_token');
                this.commit('mute', false);
            }
        },
	
		/**
		 * This makes sure that loaded token is valid on the backend side
		 * However if not, it will drop current token
		 */
		validateToken() {
			// Don't proceed if previous session doesn't exist
			if(!Mixin.methods.tokenExists(this.state.access_token)) {
				return;
            }
            
            // Lock
            this.state.validating_token = true;

			// Ask backend if this token is still valid
			axios.post('/api/auth/checkToken')
			.then(response => {
                // API will always return true in success field, if not
                // token is invalid and logout
                if(!response) {
                    this.dispatch('dropToken');
                }
			}, err => {
                // In case of any errors, logout as well
				this.dispatch('dropToken');
			}).finally(_ => {

                // Unlock
                this.state.validating_token = false;

            });
        },

	},

    getters: {

    }
});
