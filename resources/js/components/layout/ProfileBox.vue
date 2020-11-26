<template>
    <div class="profile-box">
        <div class="profile-box__username">
            <div class="profile-box__username-wrapper">
                <span :style="computeStyling">{{ username }}</span>
                <div class="stats-level">{{ level }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProfileBox",
    computed: {
        username: function() {
            return this.$store.state.user.username;
        },
        computeStyling: function() {
            return "display: " + "normal" + ";";
        },
        level: function() {
            if(this.$store.state.stats.level) {
                return this.$store.state.stats.level.value || 0;
            }
        },
        money: function() {
            if(this.$store.state.stats.money) {
                return this.$store.state.stats.money.value || 0;
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '../../styles/variables.scss';
.profile-box {

    width: 100%;

    &__username {
        text-align: center;
        letter-spacing: 2px;
        font-weight: 500;
        font-size: 0.8em;  
    }

    &__username-wrapper {
        // padding: 0px 2px 5px 2px;
        white-space: nowrap;
        display: flex;
        justify-content: center;
        flex-flow: column;
        align-items: center;
        border-bottom: 1px solid #fff;
        width: 90%;
        margin: 0 auto;
    }

    .stats-level {
        padding: 2px 20px 2px 20px;
        // border-radius: 10%;
        border: 1px solid #fff;
        color: $sidebar-background;
        background-color: #fff;
        font-family: 'Josef Sans', 'Nunito', 'Roboto', sans-serif;
    }
}
</style>