<template>
    <main class="content">
        <div class="container p-0">

            <h1 class="h3 mb-3">Messages</h1>

            <div class="card">
                <div class="row g-0">

                    <div class="col-12 col-lg-7 col-xl-9">




                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                            <div class="d-flex align-items-center py-1">

                                <div class="flex-grow-1 pl-3">
                                    <strong>Group Chat</strong>
                                </div>

                            </div>
                        </div>



                        <div class="position-relative">
                            <div class="chat-messages p-4">

                                <div v-for="(m, i) in allmessages" :key="i"
                                    :class="(userData.id == m.user.id) ? 'chat-message-right mt-2' : 'chat-message-left mt-2'">
                                    <div>
                                        <!-- <img src="" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40"> -->
                                        <div class="text-muted small text-nowrap mt-2">{{ m.created_at }}</div>
                                    </div>
                                    <div class="flex-shrinv-1 bg-light rounded py-2 px-3 mr-3">
                                        <div class="font-weight-bold mb-1">{{ m.user.name }}</div>
                                        {{ m.message }}
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>

                        <div class="flex-grow-0 py-3 px-4 border-top">
                            <div class="input-group">
                                <input type="text" id='chatMessage' class="form-control" v-model="message"
                                    placeholder="Type your message">
                                <button class="btn btn-primary" id="sendMessage" @click="sendMessage()">Send</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    props: ['userData'],
    data() {
        return {
            allmessages: [],
            message: null,


        }
    },

    mounted() {
        Echo.private('lchat')
            .listen('MessageSent', (e) => {
                this.allmessages.push(e.message);
                setTimeout(this.scrollToEnd, 100);
            })
    },
    methods: {
        sendMessage() {

            if (!this.message) {
                return alert('please Enter Message');
            }
            axios.post('/send-message', { 'message': this.message }).then(response => {
                this.message = null;
                this.fetchMessage();
                setTimeout(this.scrollToEnd, 100);

            });
        },
        fetchMessage() {
            axios.get('/messages').then(response => {
                this.allmessages = response.data;
            });

        },
        scrollToEnd() {
            window.scrollTo(0,99999)
        }
    },

    created() {
        this.fetchMessage();
    },





}
</script>
