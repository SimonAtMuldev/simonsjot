<template>
    <div class="relative pb-4">
        <label :for="name" class="contact_create_label">{{ label }}</label>
        <input :id="name" :type="type"
               class="pt-8 w-full text-gray-900 border-b pb-2 focus:outline-none focus:border-blue-400"
               :class="errorClassObjects()"
               :placeholder="placeholder" v-model="value" @input="updateField()">

        <p class="text-red-600 text-sm" v-text="errorMessages()">Error Here</p>
    </div>
</template>

<script>
    export default {
        name: "InputField",

        props: [
            'name', 'label', 'placeholder', 'type', 'errors'
        ],

        data: function () {
          return {
              value: ''
          }
        },

        computed: {
            hasError: function() {
                return this.errors && this.errors[this.name] && this.errors[this.name].length > 0;
            }
        },

        methods: {
            updateField: function () {
                if (this.name === 'name') {
                    this.value = this.value.replace(/(?:^|\s|-)\S/g, x => x.toUpperCase());
                }
                this.$emit('update:field', this.value);
                this.clearErrors(this.name);
            },

            errorMessages: function () {
                if(this.hasError) {
                    return this.errors[this.name][0];
                }
            },
            clearErrors: function() {
                if(this.hasError) {
                    this.errors[this.name] = null;
                }
            },
            errorClassObjects: function () {
                return {
                    'error-field': this.hasError
                }

            }

        }
    }
</script>

<style scoped>
    .error-field {
        @apply .border-red-500 .border-b-2
    }
</style>
