<template>
    <div>
        <StatsBox v-for="stat in stats" v-bind:key="stat.id" />
    </div>
</template>

<script>
export default {
    name: "StatsList",
    data() {
        return {
            stats: []
        }
    }
}
</script>