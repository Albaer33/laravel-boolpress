<template>
    <section>
        <div class="container">

            <h1>I nostri post</h1>

            <div class="row row-cols-3">

                <!-- Single post card -->
                <div v-for="post in posts" :key="post.id" class="col">
                    <div class="card my-2">
                        <!-- immagine da inserire in futuro -->
                        <!-- <img src="..." class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h5 class="card-title">{{ post.title }}</h5>
                            <p class="card-text">{{ truncateText(post.content, 50) }}</p>
                        </div>
                        <!-- <ul class="list-group list-group-flush">
                            <li class="list-group-item">An item</li>
                        </ul>
                        <div class="card-body">
                            <a href="#" class="card-link">Card link</a>
                        </div> -->
                    </div>
                </div>
                <!-- End Single post card -->
                
            </div>

        </div>
    </section>
</template>

<script>
export default {
    name: 'Posts',
    data: function() {
        return {
            posts: []
        };
    },
    methods: {
        getPosts: function() {
            // chiamata API per prelevare i posts dal controller backend
            axios.get('/api/posts')
            .then((response) => {
                this.posts = response.data.results;
            });
        },
        truncateText: function(text, maxCharsNumber) {
            // Prende un testo, se il testo è più lungo di x caratteri
            // lo taglia e aggiunge 3 puntini alla fine
            if(text.length > maxCharsNumber) {
                return text.substr(0, maxCharsNumber) + '...';
            }
            return text;
        }
    },
    created: function() {
        this.getPosts();
    }
}
</script>