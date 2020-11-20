<template>
    <div class="profile-box">
        <div class="profile-box__username">
            <span :style="computeStyling">{{ username }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProfileBox",
    computed: {
        username: function() {
            return this.$store.state.user.name;
        },
        computeStyling: function() {
            return "display: " + "normal" + ";";
        }
    }
}
</script>

<style lang="scss" scoped>
.profile-box {

    width: 100%;

    &__username {
        text-align: center;
        letter-spacing: 2px;
        font-weight: 500;
        white-space: nowrap;   
        font-size: 0.8em;  
        span {
            border-bottom: 1px solid #fff;
            padding: 0px 2px 5px 2px;
        }
    }
}
</style>