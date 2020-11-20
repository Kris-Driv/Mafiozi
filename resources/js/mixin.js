import Axios from "axios";
import store from './store.js';

export default {
    /**
     * Used in front-end to see if user is still authenticated
     */
    authorized: function () {
        return this.tokenExists() && store.state.access_token !== null;
    },

    /**
     * Check the existance of JWT Token
     * 
     * @param {string} token 
     */
    tokenExists: function(token) {
        if(token === undefined) {
            token = store.state.access_token;
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
                "Authorization": "Bearer " + store.state.access_token
            },
            transformResponse: (response) => {
                if(response.status === 401 && authorized()) {
                    console.log("Token expired!");
                    store.dispatch('dropToken');
                } 
            }
        });
    }
}