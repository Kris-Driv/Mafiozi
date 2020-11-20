<template>
    <div class="navigation-bar">
        <ul class="navigation-bar__list">
            <li class="navigation-bar__item navigation-bar__item--active">
                <a href="#" class="navigation-bar__link">Home</a>
            </li>
            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Jobs</a>
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
            <li class="navigation-bar__item">
                <a href="#" class="navigation-bar__link">Top</a>
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