<template>
    <footer>
        <i class="fa fa-sign-out" @click="$store.dispatch('dropToken')" aria-hidden="true"></i>
    </footer>
</template>

<script>
export default {
    name: "SidebarFooter"
}
</script>

<style lang="scss" scoped>
@import '../../styles/variables';

footer {
    border-top: 1px solid $border-color;
    border-bottom: 1px solid $border-color;
    
    width: 100%;
    height: 40px;

    font-size: 2.5em;
    color: $bg-color;
    padding: 2px;
    margin-bottom: 2px;

    &:hover {
        cursor: pointer;
    }
}
</style>

