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
    }
  },
  created() {
    // Mute the application, user should not be able to interact
    this.mute();

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