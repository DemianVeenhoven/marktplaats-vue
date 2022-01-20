<template>
    <div>
        <div>
            <b-modal v-model="showError" title="Error" variant="danger" hide-footer>{{getError}}</b-modal>
        </div>

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

                <br>

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

            <b-button-group>
                <b-button @click="submitEdit()" variant="primary">Edit</b-button>

                <b-button
                    v-if="!ad.premium"
                    :to="{ name: 'ad.premium', params: {id: adId}}" 
                    variant="success"
                >Upgrade to premium</b-button>
                
                <b-button @click="deleteAd()" variant="danger">Delete</b-button>
            </b-button-group>
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

            multiselectArray: [],

            showError: false
        }
    },

    computed: {
        ...mapGetters({
            categories: "categories/getAll"
        }),

        adId() {
            return parseInt(this.$route.params.id);
        },

        getAd() {
            const singleAd = this.$store.getters["ads/getSingleAd"](this.adId);

            if(singleAd) {
                this.ad = JSON.parse(JSON.stringify(singleAd));
                
                if(!this.multiselectArray.length) {
                    this.ad.categories.forEach(category => this.multiselectArray.push(category));
                }

                this.ad.image = null;

                this.ad.categories = [];

                return JSON.parse(JSON.stringify(singleAd));
            } 

            return {};
        },

        getError() {
            const errorMessage = this.$store.getters["auth/getError"];

            if (errorMessage) {
                this.showError = true;
                this.$store.commit("auth/CLEAR_ERROR");
                return errorMessage
            }

            return null;
        }
    },

    methods: {
        dropDocument(event) {
            this.ad.image = event.dataTransfer
                ? event.dataTransfer.files[0]
                : event.target.files[0];
        },

        submitEdit() {
            this.showError = false;
            
            const payload = {id: this.adId};
            const formData = new FormData();
            
            this.ad.categories = this.multiselectArray.map(function(item) {
                return item.id
            });
            // note to self: formData.append('_method', 'PUT') for image in an edit
            formData.append('_method', 'PUT')
            formData.append("title", this.ad.title);
            formData.append("description", this.ad.description);
            formData.append("categories", this.ad.categories);

            if (this.ad.image != null) {
                formData.append("image", this.ad.image);
            }
            
            payload.formData = formData;

            this.$store.dispatch("ads/edit", payload);
        },

        deleteAd() {
            if(!this.ad.bids.length || this.ad.bids[0].amount < 500) {
                this.$store.dispatch("ads/remove", this.ad.id);
            } else {
                this.$router.push({name: "ad.fee"});
            }
        },

        addCategory() {
            this.showError = false;

            this.$store.dispatch("categories/create", this.category).then(
                (newCategoryItem) => {
                    this.multiselectArray.push(newCategoryItem);
            });
            
            this.category.name = "";
        }
    }
}
</script>

<style scoped>
    
</style>