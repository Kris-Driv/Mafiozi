
<script>
const DEFAULT_TRANSITION = "slide";

export default {
  props: ["transitionName"],
  created() {
    this.$router.beforeEach((to, from, next) => {
      let transitionName = to.meta.transitionName || from.meta.transitionName;
      if (transitionName === "slide") {
          // Get the order, t.i. index of route
          const search = (route) => {
              for(let i = 0; i < this.$router.options.routes.length; i++) {
                  if(this.$router.options.routes[i].name === route.name) {
                      return i;
                  }
              }
              return -1;
          }

          let toIndex = search(to);
          let fromIndex = search(from);

          transitionName = toIndex > fromIndex ? 'slide-right' : 'slide-left';
      }
      this.$store.state.transitionName = transitionName || DEFAULT_TRANSITION;
      next();
    });
  },
};
</script>

<style>
.slide-left-enter-active,
.slide-left-leave-active,
.slide-right-enter-active,
.slide-right-leave-active {
  transition-duration: 0.5s;
  transition-property: height, opacity, transform;
  transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
  overflow: hidden;
}

.slide-left-enter,
.slide-right-leave-active {
  opacity: 0;
  transform: translate(2em, 0);
}

.slide-left-leave-active,
.slide-right-enter {
  opacity: 0;
  transform: translate(-2em, 0);
}
</style>