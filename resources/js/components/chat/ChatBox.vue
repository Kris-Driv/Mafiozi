<template>
    <div class="chat-box" :class="{'chat-box--minimized': minimized}">
        <div @click="minimize()" class="chat-box__controls">
            <span><i class="fa fa-comment-o" :class="{'fa-comment': !read}" aria-hidden="true"> Chat</i></span>
            <i class="fa fa-angle-up" :class="{'fa-angle-down': !minimized}" aria-hidden="true"></i>
        </div>
        <vue-simplebar class="chat-box__messages" ref="message-box">
            <ChatMessage v-for="message in messages" v-bind:key="message.id" 
                :id="message.id" 
                :content="message.content"
                :userId="message.userId" 
            />
        </vue-simplebar>
        <div class="chat-box__inputs">
            <input v-model="input" type="text">
        </div>
    </div>
</template>

<script>
import ChatMessage from './ChatMessage.vue';

export default {
    name: "ChatBox",
    components: {
        ChatMessage
    },
    data() {
        return {
            read: true,
            newMessages: 0,
            messages: [
                {
                    id: 1,
                    content: "Lorem ipsum",
                    userId: '1'
                },
                {
                    id: 2,
                    content: "Lorem ipsum",
                    userId: 'Chris'
                },
            ],
            limit: 20,
            minimized: true,
            input: ""
        }
    },
    methods: {
        addMessage(message) {
            this.push(message);
            this.cap(this.limit);
        },
        cap(limit) {
            if(this.messages.length <= limit) return;
            this.messages = this.messages.reverse().splice(0, limit+1).reverse();
        },
        removeMessage(messageId) {
            this.messages = this.messages.filter(message => {
                if(message.id !== messageId) return message;
            });
        },
        minimize() {
            this.minimized = !this.minimized;
        }
    }
}
</script>

<style lang="scss" scoped>
@import '../../styles/variables.scss';

.chat-box {
    position: fixed;
    bottom: 0px;
    right: 30px;

    width: 370px;
    height: 300px;
    border-radius: 5px 5px 0px 0px;

    display: flex;
    flex-flow: column;
    justify-content: space-between;
    overflow: hidden;

    transition: bottom 200ms;

    &--minimized {
        bottom: -270px;
    }

    &__controls {
        height: 30px;
        width: 100%;
        background-color: $accent-color;
        text-align: center;
        color: #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;

        box-shadow: 0px -2px 8px #0c0c0c;
        z-index: 1;

        a {
            color: #fff;
            font-size: 1em;
            text-decoration: none;
            font-weight: bold;
        }
    }
    &__messages {
        height: 200px;
        width: 100%;
        background-color: $sidebar-background;
        padding: 10px;
        flex: 2;
    }
    &__inputs {
        width: 100%;
        input {
            width: 100%;
            border: none;
            height: 35px;
            outline-style: solid;
            outline-width: 1px;
            outline-color: $accent-color;
            padding: 10px;
        }
        background-color: #ecf0f1;
    }
}
</style>