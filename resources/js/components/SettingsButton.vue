<template>
    <div class="settings-button" @click="toggleSettings">
        <i class="fa fa-cog" aria-hidden="true"></i>
    </div>
</template>

<script>
export default {
    name: "SettingsButton",
    methods: {
        toggleSettings() {
            // TODO, but for now, lets use it as sign out button :)
            this.$store.dispatch('dropToken');
        }
    }
}
</script>

<style lang="scss" scoped>
@import '../styles/variables.scss';

.settings-button {
    position: absolute;
    right: 0;
    top: 0;
    z-index: 0;
    border: 1px solid $bg-color;
    width: 40px;
    height: 41px;
    border-radius: 0px 0px 0px 4px;
    background-color: #a1a1a1;
    display: flex;
    justify-content: center;
    align-items: center;
    color: $fg-color;
    &:hover { cursor: pointer; }

    i {
        font-size: 18px;
    }
}
</style>