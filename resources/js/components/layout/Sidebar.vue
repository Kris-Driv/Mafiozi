<template>
    <div class="sidebar">
        <div class="wrapper">

            <Logo />

            <div class="sidebar__info">
                <ProfileBox />
                <StatsList />
            </div>
            
            <div class="sidebar__footer">
                <span>2020 Mafiozi ©</span>
                <br>
                <span>All rights reserved</span>
            </div>

        </div>
    </div>
</template>


<script>
import '../../styles/layout/sidebar.scss';
import Logo from '../Logo.vue';
import ProfileBox from './ProfileBox.vue';

export default {
    name: "Sidebar",
    components: {
        Logo, ProfileBox
    },
    methods: {},
    data() {
        return {};
    },
}
</script>