<template>
    <section>
        <div class="container">
            <h1>{{ post.title }}</h1>

            <div v-if="post.category">Categoria: {{ post.category.name }}</div>

            <div v-if="post.tags.length > 0">
                <span v-for="tag in post.tags" :key="tag.id" class="badge badge-pill badge-info mx-1 p-1">
                    {{ tag.name }}
                </span>
            </div>
            <img v-if="post.cover" :src="post.cover" class="card-img-top" alt="post.title">

            <p>{{ post.content }}</p>
        </div>
    </section>
</template>

<script>
export default {
    name: 'PostDetails',
    data: function() {
        return {
            post: false
        };
    },
    methods: {
        getPost() {
            axios.get('/api/posts/' + this.$route.params.slug)
            .then((response) => {
                if(response.data.success) {
                    this.post = response.data.results;
                } else {
                    this.$router.push({ name: 'not-found' });
                }
            });
        }
    },
    created: function() {
        this.getPost();
    }
}
</script>