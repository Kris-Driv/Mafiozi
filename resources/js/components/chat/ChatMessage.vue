<template>
    <div class="chat-message"
        :class="{'chat-message--own': own}">
        <div class="chat-message__user-avatar">
            <a href="#" class="avatar-link">
                <img src="" alt="">
            </a>
        </div>
        <div class="chat-message__username">
            <span class="username">{{ username }}</span>
        </div>
        <div class="chat-message__content">
            <span class="message">{{ content }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "ChatMessage",
    props: ["user", "content", "id", "date"],
    data() {
        return {
            own: false
        }
    },
    computed: {
        username() {
            return this.user.username;
        }
    },
    created() {
        this.own = this.user.id === this.$store.state.user.id;
    }
}
</script>

<style lang="scss" scoped>
@import '../../styles/variables.scss';

.chat-message {
    position: relative;
    font-size: 12px;
    width: 90%;

    float: left;
    background-color: rgba(#FFF, 0.03);

    &--own {
        float: right;
        background-color: rgba($accent-color, 0.1);
    }

    &__user-avatar {
        width: 24px;
        height: 24px;
        border: 4px;
        background-color: $accent-color;
        float: left;
        margin-right: 10px;
    }

    &__username {
        font-weight: 800;
        font-size: 10px;
    }

    padding: 5px;
    margin-bottom: 8px;
}
</style>