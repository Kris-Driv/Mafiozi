<template>
    <div>
        <ul v-for="stat in stats" :key="stat.type">
            <li v-if="stat.type !== 'level'">
                <StatValue 
                    :name="stat.type" 
                    :value="stat.value" 
                    :max="stat.max" 
                    :countdown="stat.local_next" 
                />
            </li>
        </ul>
    </div>
</template>

<script>
// Not used currently, will update only before value changes
const SYNCHRONIZE_FREQUENCY = 5;

import StatValue from '../StatValue.vue';

export default {
    computed: {
        stats() {
            return this.$store.state.stats;
        }
    },
    components: { StatValue },
    methods: {
        update() {
            for(let key in this.stats) {
                let stat = this.stats[key];

                // Some stats are not necessary to update
                //if(key === "energy") console.log({message: "Conditions:", intervalBiggerThanNull: (stat.interval <= 0), valueCapped: (stat.value >= stat.max), timeRanOut: (stat.local_next <= 0)});
                //console.log({max: stat.max, value:stat.value});
                if(stat.interval <= 0) continue;
                if(stat.value >= stat.max) continue;
                if(stat.local_next <= 0) continue;

                stat.local_next -= 1;
                
                // Due to latency, let's start request second earlier.
                if(stat.local_next === 0) {
                    this.$store.dispatch('retrieveUserData');
                }

                if(stat.local_next <= 0) {
                    stat.value = parseInt(stat.value) + parseInt(stat.growBy);
                    stat.local_next = parseInt(stat.interval);
                }
            }

            setTimeout(this.update, 1000);
        }
    },
    created() {
        // Initiate the loop ...
        this.update(); // Will keep looping!
    }
}
</script>