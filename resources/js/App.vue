<template>
    <div>
        <h1>{{ message }}</h1>
        <router-view/>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: "App Hello World",
        };
    },
};
</script>
