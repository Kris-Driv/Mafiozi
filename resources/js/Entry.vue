<template>
  <div :class="{muted: (muted || global_mute)}" class="wrapper" id="app">
    <AuthBox v-if="authorized() === false" />
    <Game v-else />
  </div>
</template>

<script>
// Styles
import "./styles/main.scss";

// Components
import AuthBox from "./components/auth/AuthBox.vue";
import Game from "./components/game/Game.vue";

export default {
  name: "Entry",
  components: {
    AuthBox,
    Game,
  },
  computed: {
    global_mute() {
      return this.$store.state.mute;
	},
	stats() {
		return this.$store.state.stats;
	}
  },
  watch: {
	  stats: {
		  deep: true,

		  handler() {
			//this.$store.dispatch('retrieveUserData');
		  }
	  }
  },
  created() {
    // Mute the application, user should not be able to interact
	this.mute();
	
	axios.interceptors.response.use(response => response, error => {
		// We will handle this case
		if(error.response.status === 401) {
			// Only if user was authorized before
			if(this.authorized()) {
				this.$store.dispatch('dropToken', true);

				this.$msg({
					text: "Authentication token expired, please sign in again", 
					style: "info",
					duration: 7000
				});
			} else {
				this.$msg({
					text: "You're not authorized for this action", 
					style: "error",
					duration: 7000
				});
			}


		} else {
			Promise.reject(error);
		}
	});

    (new Promise((resolve, reject) => {
		// Load and validate saved token
		this.$store.dispatch("loadTokenFromCookies");
		
		if(this.$store.state.access_token !== null) {
			resolve();
		} else {
			reject("Previous session was not found");
		}

	})).then(_ => {
		this.$store.dispatch("validateToken");
		if(this.$store.state.access_token === null) {
			throw "Saved token was dropped due to being invalid/expired";
		}
	}).then(_ => {
		this.$store.dispatch("retrieveUserData");
	})
	.catch(error => {
		console.log("Error occured while loading saved session: ", error);
	}).finally(_ => {
		this.unmute();
	});
	
  },
};
</script>