<template>
    <div :class="{muted, soft_mute}" class="jobs-view" data-simplebar>
        <carousel :autoplay="false" :data="data"></carousel>
    </div>
</template>

<script>
import ViewMixin from './ViewMixin.vue';
import Job from '../components/Job.vue';

export default {
    mixins: [ViewMixin],


    name: "JobsView",
    data() {
        return {
            data: []
        }
    },
    components: {
        Job
    },
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
    watch: {
        jobs: function(newValue, oldValue) {
            this.createElements();
        }
    },
    methods: {
        doJob(jobId) {
            console.log(jobId);
        },
        createElements() {
            if(!(this.jobs && this.jobs.forEach)) return;

            let list = [];
            this.jobs.forEach((job, index) => {
                list.push({
                    id: index,

                    content(createElement, content) {
                        return createElement(Job, {
                            props: {
                                job
                            }
                        });
                    },
                });  
            });

            this.data = list;
        }
    },
    created() {
        if(this.jobs === null) {
            this.mute(true);
            
            /* Load cached job list, and load fresh data in background */
            try {
                let savedJobs = this.$cookies.get('mafiozi.jobs');
                if(savedJobs) {
                    this.jobs = JSON.parse(savedJobs);
                }
            } catch (error) {
                // Failed to load cached list, lets delete the saved data
                // so we have the opportunity to save it properly, or at least try to
                this.$cookies.remove('mafiozi.jobs');
                console.log("Cached job list was discarded, due to being invalid");
            }

            axios.get('api/job/all').then(response => {
                this.jobs = response.data;

                this.$cookies.set('mafiozi.jobs', JSON.stringify(this.jobs));
            }).catch(err => {
                console.log("Error while retreiving job list: ", err);
            }).finally(_ => {
                this.unmute(true);
            });
        } else {
            this.createElements();
        }
    }
}
</script>

<style lang="scss">
@import '../styles/variables.scss';

.jobs-view {
    height: 80%;
    max-width: 1100px;
    width: 100%;

    h3 {
        font-family: 'Josefin Sans';
        font-weight: 200;
    }
}

.job-item {
    display: flex;
    justify-content: center;
    flex-flow: column;
    align-items: center;

    &__image {
        flex: 2;
        max-width: 500px;
        max-height: 200px;
        height: auto;
        width: auto;
        border: 1px solid #fff;
    }

    &__wrapper {
        width: 70%;
        padding: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    &__left {
        float: left;

        span {
            display: block;
        }
    }

    &__right {
        float: right;
    }

    &__description {
        width: 60%;
        margin: 20px;
    }
}

.carousel {

    min-height: 350px;
    height: 100%;

    &__list {
        height: 100%;
    }

    &__item {
        height: 95%;
        padding: 30px 0px 30px 0px;
    }

}
</style>
