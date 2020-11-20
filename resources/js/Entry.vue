<template>
  <div class="wrapper" id="app">
    <div class="mute" :class="{ 'mute--active': muted }"></div>
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
  methods: {},
  computed: {
    muted() {
      return this.$store.state.mute;
    },
  },
  created() {
    // Mute the application, user should not be able to interact
    this.$store.commit("mute", true);

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
		this.$store.commit('mute', false);
	});
	
  },
};
</script>

<style lang="scss" scoped>
.mute {
  z-index: -1000;
  background-color: transparent;
  transition: background-color 1000ms;
  width: 100vw;
  height: 100vh;

  position: fixed;
  top: 0;
  left: 0;
}

.mute--active {
  z-index: 1000;
  background-color: rgba(#fff, 0.15);

  &:hover {
    cursor: wait;
  }
}
</style>