<template>
    <div :class="{muted}" class="top-view">
        <h3>Top View</h3>
        
        <div class="player-item" v-for="player in top" :key="player.id">
            {{ player.username }}
        </div>
    </div>
</template>

<script>
import ViewMixin from './ViewMixin.vue';

export default {
    mixins: [ViewMixin],


    name: "TopView",
    data() {
        return {
            top: []
        }
    },
    created() {
        this.mute();

        axios.get('/api/user/top', {
            type: 'level'
        }).then(response => {
            this.top = response.data;
        }, error => {

            console.log("Error happened while retreiving player list", error);

        }).finally(_ => {
            this.unmute();
        });
    }
}
</script>

<style lang="scss" scoped>
.top-view {
    height: 80%;
    max-width: 1100px;
    width: 100%;
}
</style>