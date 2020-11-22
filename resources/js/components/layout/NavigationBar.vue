<template>
    <div class="navigation-bar">
        <ul class="navigation-bar__list">
            
            <!-- Home Tab -->
            <li class="navigation-bar__item navigation-bar__item--active">
                <router-link class="navigation-bar__link" :to="{ name: 'Home' }" exact>Home</router-link>
            </li>
            
            <!-- Home Tab -->
            <li class="navigation-bar__item navigation-bar__item--active">
                <router-link class="navigation-bar__link" :to="{ name: 'Jobs' }">Jobs</router-link>
            </li>

            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Tasks</a>
            </li>
            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Doctor</a>
            </li>
            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Shop</a>
            </li>
            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Character</a>
            </li>
            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Mafiozi</a>
            </li>
            
            <!-- Top Tab -->
            <li class="navigation-bar__item navigation-bar__item--active">
                <router-link class="navigation-bar__link" :to="{ name: 'Top' }">Top</router-link>
            </li>

        </ul>
    </div>
</template>

<script>
import '../../styles/layout/navigation-bar.scss';

export default {
    name: "NavigationBar",
    data() {
        // Links should be dynamic ?
        return {}
    }
}
</script>

<style scoped>
a {
    text-decoration: none;
}
</style>