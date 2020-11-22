<template>
    <div id="content">
        <NavigationBar />
        <SettingsButton />

        <transition
            :name="$store.state.transitionName"
            mode="out-in"
        >
            <router-view />
        </transition>
    </div>
</template>

<script>
import NavigationBar from '../layout/NavigationBar.vue';
import SettingsButton from '../SettingsButton.vue';

export default {
    name: "Content",
    components: {
        NavigationBar, SettingsButton
    }
}
</script>

<style lang="scss" scoped>
#content {
    display: flex;
    flex-flow: column;
    justify-content: flex-start;
    align-items: center;
}
</style>