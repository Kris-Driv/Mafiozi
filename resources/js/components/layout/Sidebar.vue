<template>
    <div class="sidebar">
        <div class="wrapper">

            <Logo />

            <div class="sidebar__info">
                <ProfileBox />
                <StatsList v-if="$store.state.stats" :stats="$store.state.stats" />
            </div>
            
            <div class="sidebar__footer">
                <SidebarFooter />
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
import StatsList from './StatsList.vue';
import SidebarFooter from './SidebarFooter.vue';

export default {
    name: "Sidebar",
    components: {
        Logo, ProfileBox, StatsList, SidebarFooter
    },
    methods: {},
    data() {
        return {};
    },
}
</script>