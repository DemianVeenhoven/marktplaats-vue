<template>
    <div>
        <h1>Edit {{this.getAd.title}}</h1>

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
                    <button @click="addCategory()">Add</button>
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

            <button @click="submitEdit()">Edit</button>
        </div>

        <div>
            <button @click="deleteAd()">Delete</button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },

    data() {
        return {
            ad: {},

            category: {
                name: ""
            },

            multiselectArray: []
        }
    },

    computed: {
        adId() {
            return parseInt(this.$route.params.id);
        },

        getAd() {
            let singleAd = this.$store.getters["ads/getSingleAd"](this.adId);

            if(singleAd) {
                this.ad = JSON.parse(JSON.stringify(singleAd));
                this.ad.categories = [];
                return JSON.parse(JSON.stringify(singleAd));
            } else {
                return {}
            }
        },

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

        submitEdit() {
            this.multiselectArray.forEach(item => this.ad.categories.push(item.id))

            if (this.ad.image == null) {
                this.$delete(this.ad, "image");
            }

            this.$store.dispatch("ads/edit", this.ad);
        },

        deleteAd() {
            this.$store.dispatch("ads/remove", this.ad.id);
        }
    }
}
</script>

<style scoped>
    
</style>