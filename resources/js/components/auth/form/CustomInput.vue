<template>
    <div class="auth-form__input-group input-group">
        <label 
            class="auth-form__input-group__label" 
            :for="name"
            :class="computeFocus"
            :ref="'custom-input-label-' + name"
        >
            {{ label }}
        </label>
        <input v-model="value" ref="input" :type="type" :name="name" 
            @focus="focusLabel" 
            @blur="unfocusLabel"
            @animationstart="handleAutofill"
            @input="updateValue"
        >
    </div>
</template>

<script>
export default {
    name: "CustomInput",
    props: ["name", "type", "label"],
    data() {
        return {
            focused: {
                type: Boolean,
                default: false
            }
        }
    },
    methods: {
        focusLabel() {
            this.focused = true;
        },
        unfocusLabel() {
            this.focused = false;
        },
        handleAutofill() {
            // console.log(this.computeFocus);
        },
        updateValue(e) {
            this.$store.commit('updateInputValue', e.target.value)
        }
    },
    computed: {
        value: {
            set(value) {
                return this.$store.commit('updateInputValue', {
                    name: this.name,
                    value
                });
            },
            get() {
                return this.$store.state['input_' + this.name];
            }
        },
        computeFocus() {
            return {
                'label-raised': this.raiseFocus
            }
        },
        raiseFocus() {
            return this.value.length > 0 || this.focused;
        }
    },
    created() {
        this.$nextTick(() => {
            this.computeFocus;
        });
    }
}
</script>

<style lang="scss" scoped>
@import './../../../styles/variables.scss';

.label-raised {
    font-size: 0.8em;
    color: $txt-grayed;
    top: -15px;
    left: 3px;
}

input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active  {
  transition: background-color 5000s;
  -webkit-text-fill-color: #fff !important;
}
</style>