<template>
    <div class="sidebar__logo logo"
        @mouseover="isHovering = true"
        @mouseout="isHovering = false"
        :class="{hovering: isHovering}"
        :style="style">
        <a href="#" title="Mafiozi"><h3>M</h3></a>
    </div>
</template>

<script>
export default {
    name: "Logo",
    data() {
        return {
            isHovering: false,
        }
    },
    props: {
        size: {
            type: Number,
            default: 50
        }
    },
    computed: {
        style() {
            return 'width: ' + this.size + 'px; height: ' + this.size + 'px;'
                 + 'font-size: ' + (this.size / 2 - 1) + 'px; padding: ' + (this.size / 10 - 1) + 'px';
        }
    }
}
</script>

<style lang="scss" scoped>
    .logo {
        margin: 30px;
        margin-bottom: 10px;
        font-family: 'Graduate', sans-serif;
        // font-size: 24px;
        //padding: 4px;
        border: 2px solid #fff;
        // width: 50px;
        // height: 50px;
        position: relative;

        &::before, &::after {
            display: inline-block;
            position: absolute;
            content: "";
            width: 20px;
            height: 20px;
            transition: all 350ms;
        }
        &::before {
            top: -10px;
            left: -10px;
            border-top: 2px solid #fff;
            border-left: 2px solid #fff;
        }
        &::after {
            bottom: -10px;
            right: -10px;
            border-bottom: 2px solid #fff;
            border-right: 2px solid #fff;
        }
        a {
            text-decoration: none;
            color: #fff;
        }
    }

    .hovering::after {
        bottom: -12px;
        right: -12px;
    }
    .hovering::before {
        top: -12px;
        left: -12px;
    }

</style>