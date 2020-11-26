import Axios from "axios";
import store from './store.js';

const LOREM = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem nesciunt laborum possimus asperiores eaque nemo saepe sequi maxime illum totam. Voluptatum eum consequuntur facere impedit cumque quae veniam labore qui."

export default {

    data() {
        return {
            muted: false,
            soft_mute: false
        }
    },

    methods: {

        /**
         * Return some dummy text
         * 
         * @param Number wordLength 
         */
        lorem_ipsum: function(wordLength) {
            // TODO, not that important
            return LOREM;
        },

        /**
         * Muting logic
         */
        mute(soft = false, both = false) {
            if(soft) {
                this.soft_mute = true;

                if(!both) return;
            }
            this.mute = true;
        },

        unmute(soft = false, both = false) {
            if(soft) {
                this.soft_mute = false;

                if(!both) return;
            }
            this.mute = false;
        },

        /**
         * Used in front-end to see if user is still authenticated
         */
        authorized: function () {
            return this.tokenExists() && this.$store.state.access_token !== null;
        },

        /**
         * Check the existance of JWT Token
         * 
         * @param {string} token 
         */
        tokenExists: function(token) {
            if(token === undefined) {
                token = this.$store.state.access_token;
            }

            return token !== null || token !== undefined || token !== "null" || isString(token) && token.length > 5;
        },

        /**
         * Request handler function
         */
        request: function(method, path, data, options = {}) {
            return Axios({
                ...options,
                method,
                url: path,
                data,
                headers: {
                    "Authorization": "Bearer " + this.$store.state.access_token
                },
                transformResponse: (response) => {
                    if(response.status === 401 && authorized()) {
                        console.log("Token expired!");
                        store.dispatch('dropToken');
                    } 
                }
            });
        },

        parseStats: function(raw) {
            const stats = {};
            
            for(let key in raw) {
                let stat = raw[key];

                stats[stat.type] = {
                    value: parseInt(stat.value),
                    max: parseInt(stat.max),
                    growBy: parseInt(stat.grow_by),
                    delta: parseInt(stat.delta_time),
                    interval: parseInt(stat.update_interval),
                    type: stat.type,
                    next_update: parseInt(stat.next_update),
                    next_update_delta: parseInt(stat.next_update),
                    local_next: (stat.next_update - Math.floor(Date.now() / 1000))
                }
            }
            return stats;
        },

    }

}