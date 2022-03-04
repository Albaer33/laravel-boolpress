<template>
    <section>
        <div class="container">

            <h1>I nostri post</h1>

            <div class="row row-cols-4">

                <!-- Single post card -->
                <div v-for="post in posts" :key="post.id" class="col">
                    <div class="card my-2">
                        <!-- immagine da inserire in futuro -->
                        <!-- <img src="..." class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h5 class="card-title">{{ post.title }}</h5>
                            <p class="card-text">{{ truncateText(post.content, 50) }}</p>
                        </div>
                        
                        <div class="card-body">
                            <router-link :to="{ name: 'post-details', params: { slug: post.slug } }">Leggi articolo</router-link>
                        </div>
                    </div>
                </div>
                <!-- End Single post card -->
                
            </div>

            <!-- barra navigazione pagine TO DO -->
            <nav>
                <ul class="pagination">

                    <!-- al click va alla pagina precedente SE non si è alla pagina iniziale -->
                    <li class="page-item" :class="{ 'disabled': currentPage == 1 }">
                        <a @click="getPosts(currentPage - 1)" class="page-link" href="#">Previous</a>
                    </li>

                    <!-- al click indirizza alla pagina selezionata -->
                    <li v-for="n in lastPage" :key="n" class="page-item" :class="{ 'active': currentPage == n }">
                        <a @click="getPosts(n)" class="page-link" href="#">{{ n }}</a>
                    </li>

                    <!-- al click va alla pagina susseguente SE non si è alla pagina finale -->
                    <li class="page-item" :class="{ 'disabled': currentPage == lastPage }">
                        <a @click="getPosts(currentPage + 1)" class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>

        </div>
    </section>
</template>

<script>
export default {
    name: 'Posts',
    data: function() {
        return {
            posts: [],
            currentPage: 1,
            lastPage: false
        };
    },
    methods: {
        getPosts: function(pageNumber) {
            // chiamata API per prelevare i posts dal controller backend
            axios.get('/api/posts', {
                params: {
                    page: pageNumber
                }
            })
            .then((response) => {
                this.posts = response.data.results.data;
                // assegnazioni di pagina corrente e ultima pagina
                console.log(response.data.results);
                this.currentPage = response.data.results.current_page;
                this.lastPage = response.data.results.last_page;
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
        this.getPosts(1);
    }
}
</script>