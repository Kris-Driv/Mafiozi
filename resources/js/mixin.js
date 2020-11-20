export default {
    /**
     * Used in front-end to see if user is still authenticated
     */
    authorized: function () {
        return this.tokenExists && this.$store.state.access_token !== null;
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
    }
}