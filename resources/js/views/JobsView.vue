<template>
    <div :class="{muted, soft_mute}" class="jobs-view" data-simplebar>
        <carousel :autoplay="false" :data="data"></carousel>
    </div>
</template>

<script>
import ViewMixin from './ViewMixin.vue';

export default {
    mixins: [ViewMixin],


    name: "JobsView",
    data() {
        return {
            data: []
        }
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
        createElements() {
            console.log(this.jobs);
            let list = [];
            this.jobs.forEach((job, index) => {
                list.push({
                    id: index,

                    content(createElement, content) {
                        return createElement('div', {
                            class: `job-item job-${job.name}`,
                        }, [
                            createElement('h3', {
                                class: "job-item__name",
                            }, [
                                `${job.name}`
                            ]),
                            createElement('img', {
                                class: "job-item__image",
                                attrs: {
                                    src: "/img/job_general.png",
                                    alt: "job's illustration"
                                }
                            }),
                            createElement('p', {
                                class: "job-item__description",
                            }, [`${job.description || this.lorem_ipsum(50)}`]),
                            createElement('div', {
                                class: "job-item__wrapper"
                            }, [
                                createElement('div', {
                                    class: "job-item__left job-item__info"
                                }, [
                                    createElement('span', {
                                        class: "job-info rewards"
                                    }, [
                                        `Rewards: \$${job.rm_min} - \$${job.rm_max} and ${job.xp} XP`,
                                    ]),
                                    createElement('span', {
                                        class: "job-info cost-energy"
                                    }, [
                                        `Energy Cost: ${job.energy}`
                                    ]),
                                    createElement('span', {
                                        class: "job-info requirements-list"
                                    }, [
                                        `Requirements: none`, // TODO add requirements
                                    ]),
                                ]),
                                createElement('div', {
                                    class: "job-item__right job-item__actions"
                                }, [
                                    createElement('button', {
                                        class: "job-item__button btn",
                                        // Add event listener onclick
                                    }, ['Work'])  
                                ])
                            ]),
                        ]);
                    },
                });  
            });

            this.data = list;
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
