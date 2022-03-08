<template>
    <div>
        <section>
            <div class="container">
                <h1>Contattaci</h1>

                <form>
                    <div class="mb-3">
                        <label for="email" class="form-label">Indirizzo email</label>
                        <input type="email" class="form-control" id="email">
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Messaggio</label>
                        <textarea id="message" class="form-control" cols="30" rows="10"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Invia</button>
                </form>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    name: 'Contacts',
}
</script>