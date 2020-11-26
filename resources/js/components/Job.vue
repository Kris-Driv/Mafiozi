<template>
    <div class="job-item job-${job.name}">
        <h3 class="job-item__name">{{ job.name }}</h3>
        <img src="/img/job_general.png" class="job-item__image" alt="job's illustration" />
        <p class="job-item__description">{{ job.description || lorem_ipsum(50) }}</p>
        <div class="job-item__wrapper">
            <div class="job-item__left job-item__info">
                <span class="job-info rewards">
                    Rewards: ${{ job.rm_min }} - ${{ job.rm_max }} and {{ job.xp }} XP
                </span>
                <span class="job-info cost-energy">
                    Energy Cost: {{ job.energy }}
                </span>
                <span class="job-info requirements-list">
                    Requirements: none
                </span>
            </div>
            <div class="job-item__right job-item__actions">
                <button class="job-item__button btn" ref="perform-button" v-on:click="perform">{{ button_text }}</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Job",
    props: ["job"],
    data() {
        return {
            button_text: "Perform",
            in_job: false
        }
    },
    methods: {
        perform() {
            if(this.in_job) return;

            this.button_text = "..."

            axios.post('/api/job/do-job', {
                job_id: this.job.id
            }).then(response => {
                if(response.status === 200) {
                    if(response.data.success) {
                        console.log("Job done!");

                        let {rewards, spent} = response.data;

                        this.$msg({
                            text: `Job completed! You've received payment +\$${rewards['money']}
                                and +${rewards['xp']} XP`, style: 'success'
                        });

                        this.$store.commit('updateStatsValues', {
                            money: this.$store.state.stats.money.value + rewards['money'],
                            xp: parseInt(this.$store.state.stats.xp.value) + parseInt(rewards['xp']),
                            energy: this.$store.state.stats.energy.value - spent['energy']
                        });
                        
                        // Try to synchronize
                        this.$store.dispatch('retrieveUserData');

                    } else {
                        let message = response.data.message;

                        this.$msg({
                            text: message, style: 'warning'
                        });
                    }
                }
            }, error => {
                
                this.$msg({
                    text: "Failed to perform a job due to error", style: 'error'
                });
                console.log(error);

            }).finally(_ => {
                this.button_text = "Perform";
                this.in_job = false;
            });
        }
    }
}
</script>

<style lang="scss" scoped>

</style>