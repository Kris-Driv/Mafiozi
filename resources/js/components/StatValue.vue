<template>
    <div class="stat-value" :class="'stat-value-' + name">
        <span class="stat-value__indicator"><component :is="icon" /></span>
        <div class="stat-value__details">
            <span class="stat-value__value" v-if="value >= 0">{{ value }}</span>
            <span class="stat-value__max" v-if="max > 0"> / {{ max }}</span>
            <span class="stat-value__countdown" v-if="countdown && countdown >= 0 && value < max">({{ countdown }})</span>
        </div>
    </div>
</template>

<script>
import { upperFirst } from 'lodash';

export default {
    props: ["value", "max", "name", "countdown"],
    computed: {
        icon() {
            return {
                template: `<i class="fa ${this.getIconClass(this.name)}" title="${upperFirst(this.name)}" aria-hidden="true"></i>`
            }
        }
    },
    methods: {
        getIconClass(stat) {
            let iconClass = "";

            switch(stat) {
                case "energy":
                    iconClass = "fa-bolt";
                    break;
                case "money":
                    iconClass = "fa-money";
                    break;
                case "mafia":
                    iconClass = "fa-users";
                    break;
                case "health":
                    iconClass = "fa-heart";
                    break;
                case "stamina":
                    iconClass = "fa-heartbeat";
                    break;
                case "xp":
                    iconClass = "fa-star";
                    break;
                default:
                    iconClass = "fa-unknown";
            }
            return iconClass;
        }
    }
}
</script>

<style lang="scss" scoped>
.stat-value {
    width: 90%;
    margin: 0 auto;
    border-bottom: 1px solid #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;

    &__indicator {
        float: left;

        i {
            
        }
    }

    &__details {
        float: right;
    }
}
</style>