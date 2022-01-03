<template>
    <div>
        <h1>Create a new ad</h1>

        <div>
            <div>
                <label>Title:</label>
                <input type="text" id="title" v-model="ad.title"/>
            </div>

            <br>

            <div>
                <label>Description:</label>
                <textarea id="description" v-model="ad.description"></textarea>
            </div>

            <br>

            <div>
                <p>Categories:</p>

                <div>
                    <label>Add a category</label>
                    <input type="text" id="category" v-model="category.name">
                    <b-button @click="addCategory()" variant="primary">Add</b-button>
                </div>
                
                <div>
                    <multiselect
                        v-model="multiselectArray" 
                        :options="categories" 
                        :multiple="true" 
                        :close-on-select="false" 
                        :clear-on-select="false" 
                        placeholder="Pick some" 
                        label="name" 
                        track-by="id" 
                        :preselect-first="false" 
                        :searchable="false"
                    ></multiselect>
                </div>

                <div>
                    <label for="image">Upload image</label>
                    <input
                        id="image"
                        ref="fileLoader"
                        type="file"
                        @change="dropDocument"
                    />
                </div>
            </div>

            <br>

            <p>Upon selling something for €500.00 or more a %10 fee needs to be paid</p>

            <br>

            <b-button @click="submitAd()" variant="primary">Create ad</b-button>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import { mapGetters } from 'vuex';
import Input from '../../../../vendor/laravel/breeze/stubs/inertia-vue/resources/js/Components/Input.vue';

export default {
    components: {
        Multiselect,
        Input
    },

    created() {
        this.$store.dispatch("categories/setAll");
    },

    data() {
        return {
           ad: {
               title: "",
               description: "",
               image: null,
               categories: []
           },

           category: {
               name: ""
           },

           multiselectArray: []
        }
    },

    computed: {
        ...mapGetters({
            categories: "categories/getAll"
        })
    },

    methods: {
        dropDocument(event) {
            this.ad.image = event.dataTransfer
                ? event.dataTransfer.files[0]
                : event.target.files[0];
        },

        submitAd() {
            //note to self: formData for image
            const formData = new FormData();

            this.multiselectArray.forEach(item => this.ad.categories.push(item.id))

            formData.append("title", this.ad.title);
            formData.append("description", this.ad.description);
            console.log(this.ad.categories);
            formData.append("categories", this.ad.categories);

            if (this.ad.image != null) {
                formData.append("image", this.ad.image);
            }
            
            this.$store.dispatch("ads/create", formData);
        },

        addCategory() {
            this.$store.dispatch("categories/create", this.category)
        }
    }
}
</script>

<style scoped>

</style>
