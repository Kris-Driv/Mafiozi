<template>
    <div :class="{'muted': muted}" class="jobs-view" data-simplebar>
        <h3>Jobs View</h3>
    </div>
</template>

<script>
export default {
    name: "JobsView",
    computed: {
        jobs: {
            set(value) {
                this.$store.commit('updateJobList', value);
            },
            get() {
                return this.$store.state.jobs;
            }
        }
    },
    created() {
        if(this.jobs === null) {
            this.mute();

            axios.get('api/job/all').then(response => {
                this.jobs = response.data;
                console.log(this.jobs);
            }).catch(err => {
                console.log("Error while retreiving job list: ", err);
            }).finally(_ => {
                this.unmute();
            });
        }
    }
}
</script>

<style lang="scss" scoped>
@import '../styles/variables.scss';

.jobs-view {
    height: 80%;
    max-width: 1100px;
    width: 100%;
    border: 1px solid $border-color;
}
</style>