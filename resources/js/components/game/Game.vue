<template>
    <div class="game-wrapper">
        <Sidebar />
        <Content />
        <ChatBox />
        <!-- Nothing should be placed outside here -->
    </div>
</template>

<script>
import Sidebar from '../layout/Sidebar.vue';
import ChatBox from '../chat/ChatBox.vue';
import Content from './Content.vue';

export default {
    name: "Game",
    components: {
        Sidebar, ChatBox, Content
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