<template>
    <div>
        <section class="gradient-custom">
            <div class="container py-5">

                <div class="dropdown">
                    <button class="dropbtn">
                        <i class="fa fa-bell"></i>
                        <span class="badge badge-danger">{{ notification.length }}</span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content notification-container" @scroll="handleScroll" ref="notificationContainer">

                        <div class="media p-2" v-for="(n, i) in notification" :key="i">
                            <img class="mr-2" style="height: 60px;width: 60px;"
                                src="https://www.kindpng.com/picc/m/495-4952535_create-digital-profile-icon-blue-user-profile-icon.png"
                                alt="commenter image">
                            <div class="media-body">
                                <div class="mt-0"><strong>Your friend {{ n.data.conversation_owner }}</strong> added you to
                                    a new group called {{ n.data.conversation_name }}
                                </div>
                                <p class="m-0"><i class="fa fa-clock-o mr-1"></i> 324 </p>

                            </div>
                        </div>
                        <div>
                            <a href="/admin/notifications" class="see-all"><i class="fa fa-bell-o mr-2"></i>See All </a>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-lg-5 col-xl-5 mb-4 mb-md-0">


                        <h5 class="font-weight-bold mb-3 text-center text-white">Groups</h5>

                        <div class="card mask-custom">
                            <div class="card-body">

                                <ul class="list-unstyled mb-0">
                                    <li class="p-2 border-bottom" v-for="group in groups" :key="group.id" :id="group.id"
                                        @click="activeGroupfun(group.id)"
                                        style="border-bottom: 1px solid rgba(255,255,255,.3) !important;">
                                        <a href="#!" class="d-flex justify-content-between link-light">
                                            <div class="d-flex flex-row">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-8.webp"
                                                    alt="avatar"
                                                    class="rounded-circle d-flex align-self-center me-3 shadow-1-strong"
                                                    width="60">
                                                <div class="pt-1" :id="group.id">
                                                    <p class="fw-bold mb-0">{{ group.name }}</p>
                                                    <p ref="myLastMessage" :id="group.id" class="small  text-white"
                                                        v-if="group.last_message">{{
                                                            group.last_message.body }}</p>
                                                </div>
                                            </div>
                                            <div class="pt-1">
                                                <p ref="myLastMessage" :id="'just_now' + group.id"
                                                    class="small text-white mb-1" v-if="group.last_message_at">{{
                                                        group.last_message_at }}</p>
                                                <span v-if="group.hasRead == null" :id="group.id"
                                                    class="circle-icon"></span>
                                            </div>
                                        </a>
                                        <span @click="deleteGroup(group)" v-if="group.user_id == user.id">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                                <path
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                            </svg>
                                        </span>
                                    </li>

                                </ul>

                            </div>


                        </div>
                        <div class="col-md-6 col-lg-5 mt-4" style="flex-direction: column;">
                            <button @click="toggleDropdown" class="btn btn-primary rounded-pill"><i
                                    class="fas fa-plus-circle"> New Group</i>
                            </button>
                        </div>
                        <div v-if="showDropdown">
                            <div>
                                <input type="text" v-model="AddGroup.GroupName" class="mt-2" placeholder="Group Name"
                                    style="font-weight:bold; border-radius: 10px;height: 50px;">
                                <div>
                                    <span class="error" v-if="errors.GroupName">{{ errors.GroupName[0] }}</span>
                                </div>
                                <h2 style="color: #007bff;">Members</h2>
                                <div v-for="(user, index) in users" :key="index" @click="AddMember(user.id)"
                                    class="user-details" style="display:inline-block">
                                    <div v-if="AddGroup.Members.includes(user.id)"
                                        :style="(AddGroup.Members.includes(user.id)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>
                                        </h2>
                                    </div>
                                    <div style="position: relative;">
                                        <img :src="'/images/' + user.image" alt="User Photo" />
                                    </div>
                                    <p class="" style="color: #0056b3;font-weight:bolder">{{ user.name }}</p>
                                </div>
                                <div>
                                    <span class="error" v-if="errors.Members">{{ errors.Members[0] }}</span>
                                </div>
                            </div>
                            <div class="user-details" style="display:inline-block">
                                <button @click="AddNewGroup" class="btn btn-primary rounded-pill">Add</button>
                            </div>
                        </div>

                    </div>
                    <div>

                    </div>



                    <div class="col-md-6 col-lg-7 col-xl-7" v-if="activeGroup != null">

                        <ul class="list-unstyled text-white">
                            <!-- sender -->
                            <li class="d-flex justify-content-between mb-4" v-for="(message, index) in groupMessages"
                                :key="index">

                                <div class="card mask-custom">
                                    <router-link :to="{ name: 'private-chat', params: { 'id': message.user_id } }">
                                        <img v-if="user.id != message.user_id" :src="'/images/' + message.user.image"
                                            alt="avatar" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong"
                                            width="60">
                                    </router-link>
                                    <div class="card-header d-flex justify-content-between p-3"
                                        style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">{{ message.user.name }}</p>
                                        <p class="text-light small mb-0">
                                            <i class="far fa-clock"></i> 12 mins ago
                                        </p>
                                    </div>
                                    <div class="card-body" :style="(message.attachment) ? { 'height': '120px' } : ''"
                                        v-if="message.attachment != null && message.mime_type == null">
                                        <p class="mb-1 mt-0 text-center">
                                            {{ message.body }}
                                        </p>
                                        <div class="image-container rounded"
                                            style="width:200px;height: 200px; overflow: hidden;">
                                            <img :src="message.attachment" alt="" style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="card-body" :style="(message.attachment) ? { 'height': '120px' } : ''"
                                        v-if="message.attachment != null && message.mime_type != null && message.body == null">
                                        <div class="image-container rounded"
                                            style="width:200px;height: 200px; overflow: hidden;">
                                            <audio ref="audioPlayer" :src="message.attachment" alt="" style="width: 100%;"
                                                controls></audio>
                                        </div>
                                    </div>
                                    <!-- show image selected -->
                                    <div class="card-body"
                                        v-if="message.attachment == null && message.mime_type == null && message.body != null">
                                        <p class="mb-1 mt-0 text-center">
                                            {{ message.body }}
                                        </p>
                                    </div>

                                </div>
                            </li>
                            <div v-if="activeGroup">
                                <li class="mb-3">
                                    <div class="form-outline form-white">
                                        <textarea placeholder="Type Message" class="form-control" id="textAreaExample3"
                                            v-model="message" rows="4"></textarea>
                                        <span class="error" v-if="errors.body">{{ errors.body[0] }}</span>
                                        <label class="form-label" for="textAreaExample3"></label>
                                    </div>
                                    <div class="form-outline form-white">
                                        <input type="file" id="attachment-input" ref="attachmentInput"
                                            style="display: none;" @input="handleFileSelection">
                                        <button class="attachment-button" id="attachment-button"
                                            @click="openFilePicker">choose file</button>
                                        <div class="containerrecord">
                                            <i @click="MicroPhoneClick"
                                                :style="(spinnerloading) ? { 'color': 'blue', 'fontSize': '30px' } : ''"
                                                class="fas fa-microphone"></i>
                                            <div class="hollow-dots-spinner" v-if="spinnerloading">
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div lass="image-container" v-if="url"
                                        style="width:100px;height: 100px;">
                                        <img :src="url" alt="" style="width: 100%;">
                                        <span class="remove-icon" @click="removeImage">&#10006;</span>
                                    </div>

                                </li>
                                <button @click="sendMessage" type="button" :disabled="isButtonDisabled"
                                    class="btn btn-light btn-lg btn-rounded float-end">Send</button>
                            </div>
                        </ul>

                    </div>
                </div>


            </div>
        </section>
    </div>
</template>
<script>

export default {

    data() {
        return {
            groups: [],
            user: [],
            errors: [],
            activeGroup: '',
            groupMessages: [],
            message: '',
            showDropdown: false,
            users: [],
            AddGroup: {
                GroupName: '',
                Members: [],
            },
            url:false,
            attachment: '',
            newgroupId: '',
            notification: [],
            currentPage: 1,
            lastPage: 1,
            isFetchingData: false,
            isAllDataFetched: false,
            menuX: 0,
            mediaRecorder: null,
            recordedChunks: [],
            menuY: 0,
            mediaRecorder: null,
            recordedChunks: [],
            clickCount: 0,
            isRecording: false,
            spinnerloading: false,
            recordingcreated: false,


        }
    },
    mounted() {
        this.getUnreadNotifications();
        this.getusers();
    },
    computed: {
        isButtonDisabled() {
            return !(this.message || this.url || this.recordingcreated);
        }

    },
    methods: {
        getusers() {
            axios.get('/get-users').then((res) => {
                this.users = res.data.users;
            }).then((err) => {
            })

        },
        openFilePicker() {
            this.$refs.attachmentInput.click();
        },
        handleFileSelection(e) {
            const file = e.target.files[0];
            this.attachment = file;
            this.url = URL.createObjectURL(file);
            URL.revokeObjectURL(file)
        },

        MicroPhoneClick() {
            if (this.clickCount == 2) {
                this.clickCount = 0
            }
            this.clickCount++
            if (this.clickCount === 1) {
                this.startRecording();

            } else if (this.clickCount === 2) {
                this.stopRecording();
            }
        },
        startRecording() {
            console.log('start')

            this.spinnerloading = true;
            if (this.isRecording) {
                return;
            }


            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.mediaRecorder.addEventListener('dataavailable', event => {
                        if (event.data.size > 0) {
                            this.recordedChunks.push(event.data);
                        }
                    });
                    this.mediaRecorder.start();
                    this.isRecording = true;
                })
                .catch(error => {
                    console.error('Error accessing microphone:', error);
                });
        },
        stopRecording() {
            console.log('stop')
            if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                this.mediaRecorder.stop();
                this.recordingcreated = true;
                this.isRecording = false;
                this.spinnerloading = false;
                console.log(this.mediaRecorder)
            }
        },


        removeImage() {
           this.url = false;

        },

        fetchGroups() {
            axios.get('/get-groups').then(response => {
                this.groups = response.data.groups;
            });

        },
        fetchMessage() {
            axios.get(`/conversation/${this.activeGroup}`).then(response => {
                this.groupMessages = response.data.groupMessages;
                let searchgroup = this.groups.find(group => group.id === this.activeGroup);
                const cyrcleSpan = document.querySelector(`span[id="${searchgroup.id}"]`);
                if (cyrcleSpan) {
                    cyrcleSpan.remove();
                }
            });

        },
        activeGroupfun(groupId) {
            Echo.leave(`PrivateGroupChat.${this.activeGroup}`);
            this.activeGroup = groupId

        },

        sendMessage() {
            const conversationId = this.activeGroup;
            const formData = new FormData();
            if (this.recordedChunks.length === 0) {
                this.recordingcreated = false;
                console.warn('No recorded audio available.');
            }
            const blob = new Blob(this.recordedChunks, { type: 'audio/webm' });
            formData.append('audio', blob, 'recorded_audio.webm');
            formData.append('image', this.attachment);
            formData.append('body', this.message),
                formData.append('conversation_id', conversationId)
            axios.post(`/create-message`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },


            }

            ).then(response => {
                this.message = null;
                this.groupMessages.push(response.data)
                this.url = false;
                this.recordedChunks = [];
                this.message = null;
                this.recordingcreated = false;
                this.errors = [];

            }).catch((error) => {
                this.errors = error.response.data.errors;
            })
        },


        // functionalty Groups

        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        AddMember(memberId) {

            let index = this.AddGroup.Members.indexOf(memberId);
            if (index !== -1) {
                this.AddGroup.Members.splice(index, 1);
            } else {
                this.AddGroup.Members.push(memberId)
            }

        },
        AddNewGroup() {
            axios.post(`/add-new-group`, { 'group': this.AddGroup }).then(response => {
                this.groups.push(response.data.groups);
                this.newgroupId = response.data.groups.id;
                this.AddGroup.Members = [];
                this.AddGroup.GroupName = '';
                this.errors = [];
                this.showDropdown = false;
                this.$fire({
                    title: `${response.data.groups.name}`, text: "has been Created Successfully", type: "success", timer: 2000
                });
            }).catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        deleteGroup(group) {
            let index = this.groups.indexOf(group);
            if (index !== -1) {
                axios.post(`/delete-group`, { 'groupId': group.id }).then(response => {
                    this.groups.splice(index, 1);
                    this.activeGroup = null;
                    this.$fire({
                        title: `${response.data.groupName}`, text: "has been deleted Successfully", type: "error", timer: 2000
                    });
                });
            }

        },

        //Notifications methods

        getUnreadNotifications() {
            if (this.currentPage > this.lastPage || this.isAllDataFetched) {
                return;
            }
            this.isFetchingData = true;
            axios
                .get('/getUnreadNotifications', {
                    params: {
                        page: this.currentPage,
                    },
                })
                .then((response) => {
                    const newNotifications = response.data.data;
                    const existingNotifications = this.notification.map((item) => item.id);
                    const filteredNotifications = newNotifications.filter((item) => !existingNotifications.includes(item.id));
                    this.notification = [...this.notification, ...filteredNotifications];
                    this.currentPage = response.data.current_page;
                    this.lastPage = response.data.last_page;
                    this.isFetchingData = false;
                    if (this.currentPage > this.lastPage) {
                        this.isAllDataFetched = true;
                    }
                })
                .catch((error) => {
                    console.log('Error fetching data:', error);
                    this.isFetchingData = false;
                });
        },
        handleScroll() {
            const container = this.$refs.notificationContainer;
            const scrollHeight = container.scrollHeight;
            const scrollTop = container.scrollTop;
            const clientHeight = container.clientHeight;

            // Check if the scroll position is near the bottom of the container
            if (scrollHeight - scrollTop - clientHeight < 10) {
                this.getUnreadNotifications();
                this.currentPage++
            }
        },

    },

    watch: {
        activeGroup(val) {
            this.fetchMessage();
        },
        newgroupId(val) {

        }

    },

    created() {
        this.user = window.authUser;
        Echo.channel('AddNewGroup')
            .listen('AddNewGroup', (e) => {
                if (e.available_users.includes(this.user.id))
                    this.groups.push(e.converstion)
            });

        this.fetchGroups();
        this.$watch('activeGroup', (newValue) => {
            if (newValue) {
                Echo.leave(`PrivateGroupChat.${this.activeGroup}`);
                Echo.private('PrivateGroupChat.' + this.activeGroup)
                    .listen('PrivateGroupSent', (e) => {
                        this.groupMessages.push(e.message);
                    })
            }
        });
        Echo.channel('pushtosidebar')
            .listen('PushToSideBar', (e) => {
                const paragraphElement = document.querySelector(`p[id="${e.conversation.id}"]`);
                const justNow = document.querySelector(`p[id="just_now${e.conversation.id}"]`);
                const spanElement = document.querySelector(`span[id="${e.conversation.id}"]`);
                if (spanElement) {
                    spanElement.remove();
                }
                if (paragraphElement) {
                    justNow.
                        insertAdjacentHTML
                        ('afterend', '<span class="circle-icon"  id="' + e.conversation.id + '" style="display: inline-block;width: 1rem;height: 1rem;border-radius: 50%;background-color: blue;"></span>')
                    paragraphElement.innerText = `${e.message}`;
                    justNow.innerText = `${e.conversation.last_message_at}`;
                }
            });
        Echo.private('App.Models.User.' + this.user.id)
            .notification((notification) => {

                this.notification.unshift(notification)
                console.log('notif', notification);
            });



    }
}




</script>
<style scoped>
.error {
    color: red;
}

.attachment-button {
    width: 108px;
    margin-right: 50px;
    padding: 10px;
    height: 44px;
}

.containerrecord {
    display: inline;
}

.fa-microphone {
    cursor: pointer;
    color: brown;
    font-size: 20px;
}

.gradient-custom {
    /* fallback for old browsers */
    background: #fccb90;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to bottom right, rgba(252, 203, 144, 1), rgba(213, 126, 235, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to bottom right, rgba(252, 203, 144, 1), rgba(213, 126, 235, 1))
}

.mask-custom {
    background: rgba(24, 24, 16, .2);
    border-radius: 2em;
    backdrop-filter: blur(15px);
    border: 2px solid rgba(255, 255, 255, 0.05);
    background-clip: padding-box;
    box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
}

.user-details {
    display: flex;
    align-items: center;
}

.user-details img {
    margin-right: 10px;
    width: 50px;
    height: 50px;
}
.remove-icon:hover{
    cursor: pointer;
}

.dropdown .dropbtn {
    font-size: 16px;
    border: none;
    outline: none;
    color: white;
    padding: 14px 16px;
    background-color: inherit;
    font-family: inherit;
    margin: -255px;
}

.navbar a:hover,
.dropdown:hover .dropbtn {
    background-color: #686e73;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 24px;
    left: -188px;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    min-width: 200px;
    max-height: 400px;
    overflow: auto;
    width: 300px
}

.dropdown-content .media-body>div {
    font-size: 15px;
    line-height: 1.3;
}

.dropdown-content .media-body a {
    float: right;
    color: #1580dc;
    background: none;
    text-decoration: none;
    display: block;
    text-align: left;
}

.see-all {
    color: #000;
    background: #e4dede;
    text-decoration: none;
    text-align: center !important;
    display: block;
    padding: 4px;
}

.dropdown-content p {
    font-size: 14px;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.sendMes {
    border-radius: 40px;
    background-color: white;
    font-size: 30px;
}

.just_now {
    color: black !important;
    font-size: 30px;
}

.lastMessage {
    color: black !important;
    border-radius: "40px";
}

.custom-menu {
    position: absolute;
    background-color: white;
    border: 1px solid gray;
    padding: 10px;
    width: 402px;
}

.menu-item {
    display: inline-block;
    align-items: center;
    margin-bottom: 10px;
}

.menu-item-content {
    display: flex;
    align-items: center;
}

.menu-item-text {
    margin-right: 10px;
}

.circle-icon {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background-color: blue;

}

.hollow-dots-spinner,
.hollow-dots-spinner * {
    display: inline-block;
    margin-left: 10px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #000;
    margin-right: 5px;
    /* تعديل هذه القيمة حسب الحاجة */
}

.hollow-dots-spinner {
    height: 15px;
    width: calc(30px * 3);
}

.hollow-dots-spinner .dot {
    width: 15px;
    height: 15px;
    margin: 0 calc(15px / 2);
    border: calc(15px / 5) solid #ff1d5e;
    border-radius: 50%;
    float: left;
    transform: scale(0);
    animation: hollow-dots-spinner-animation 1000ms ease infinite 0ms;
}

.hollow-dots-spinner .dot:nth-child(1) {
    animation-delay: calc(300ms * 1);
}

.hollow-dots-spinner .dot:nth-child(2) {
    animation-delay: calc(300ms * 2);
}

.hollow-dots-spinner .dot:nth-child(3) {
    animation-delay: calc(300ms * 3);

}

@keyframes hollow-dots-spinner-animation {
    50% {
        transform: scale(1);
        opacity: 1;
    }

    100% {
        opacity: 0;
    }
}
</style>
