<template>
    <div class="game-wrapper">
        <Sidebar />
        <div id="content" data-simplebar>
            <NavigationBar />
            <SettingsButton />

            <!-- Router #TODO -->
            <HomePage />
        </div>

        <ChatBox />
    </div>
</template>

<script>
import Sidebar from '../layout/Sidebar.vue';
import NavigationBar from '../layout/NavigationBar.vue';
import ChatBox from '../chat/ChatBox.vue';
import SettingsButton from '../SettingsButton.vue';
import HomePage from './pages/HomePage.vue';

export default {
    name: "Game",
    data() {
        // Hold the stats ?
        return {};
    },
    components: {
        Sidebar, NavigationBar, ChatBox, SettingsButton, HomePage
    }
}
</script>

<style lang="scss" scoped>
#godfather {
    position: fixed;
    right: 0px;
    bottom: 0px;
    transform: scaleX(-1);
}
</style>