<template>
    <div class="chat-box" :class="{'chat-box--minimized': minimized}">
        <div @click="minimize()" class="chat-box__controls">
            <span><i class="fa fa-comment-o" :class="{'fa-comment': !read}" aria-hidden="true"> Chat</i></span>
            <i class="fa fa-angle-up" :class="{'fa-angle-down': !minimized}" aria-hidden="true"></i>
        </div>
        <div class="chat-box__messages" ref="message-box" data-simplebar>
            <div class="messages-wrapper">
                <ChatMessage v-for="message in messages" v-bind:key="message.id" 
                    :id="message.id" 
                    :content="message.message"
                    :user="message.user"
                    :date="message.created_at" 
                />
            </div>
        </div>
        <div class="chat-box__inputs">
            <input v-model="input" type="text" @keypress="handleKeyPress">
            <button @click="sendMessage"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
        </div>
    </div>
</template>

<script>
import ChatMessage from './ChatMessage.vue';
import { uuid } from 'uuidv4';

export default {
    name: "ChatBox",
    components: {
        ChatMessage
    },
    data() {
        return {
            read: true,
            newMessages: 0,
            /*
                index => [
                    id,
                    message,
                    user => [
                        username,
                        id,
                        profile_image (path)
                    ]
                ]
            */
            messages: [],
            limit: 20,
            minimized: true,
            input: ""
        }
    },
    methods: {
        handleKeyPress(e) {
            // Enable Enter key to be send button
            if(e.charCode === 13) {
                this.sendMessage();
            }
        },
        removeMessage(msg) {
            console.log("removing message: ", msg);
        },
        sendMessage() {
            if(this.input.length < 1) {
                alert("Enter your message");
                return;
            }

            let message = {
                id: uuid(),
                message: this.input,
                user: this.$store.state.user
            };

            this.addMessage(message);
            this.input = "";

            axios.post('/api/chat/send', message).then(response => {
                if(response.data.success === true) {
                    // Okay :)
                } else {
                    this.removeMessage(message);
                }
            }).catch(error => {
                this.$msg({
                    text: "Failed to send message: " + error,
                    style: 'error'
                });
                this.removeMessage(message);
            });
        },
        addMessage(message) {
            this.messages.push(message);
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
        },
        fetchMessages() {
            // Mute chatbox
            this.mute(true);

            axios.post('/api/chat/fetch').then(response => {
                if(response.data && response.data.messages) {
                    this.messages = response.data.messages;
                }
            }).catch(error => {
                console.log("error:", error);
            }).finally(_ => {
                // Unmute chatbox
                this.mute(false);
            });
        }
    },
    mounted() {
        this.fetchMessages();

        // Subscribe to chat channel
        Echo.channel('chat')
		.listen('MessageSent', e => {
            e.message = JSON.parse(e.message).message;
			this.addMessage(e);
		});
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
        display: flex;
        justify-content: space-between;
        align-items: center;

        width: 100%;
        input {
            display: inline-block;
        }
        input[type=text] {
            width: 100%;
            border: none;
            height: 35px;
            outline-style: solid;
            outline-width: 1px;
            outline-color: $accent-color;
            padding: 10px;
        }
        background-color: #ecf0f1;

        button {
            display: inline-block;
            padding: 0 .8rem;
            height: 100%;
            color: #fff;
            background-color: $accent-color;
            border: none;

            &:hover {
                cursor: pointer;
            }
        }
    }
}
</style>