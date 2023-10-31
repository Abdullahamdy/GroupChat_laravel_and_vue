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
                                        @contextmenu="handleRightClick" @click="activeGroupfun(group.id)"
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
                                        <span @click="deleteGroup(group)">
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
                                <h2 style="color: #007bff;">Members</h2>
                                <div @click="AddMember(1)" class="user-details" style="display:inline-block">
                                    <div v-if="AddGroup.Members.includes(1)"
                                        :style="(AddGroup.Members.includes(1)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>
                                        </h2>
                                    </div>
                                    <div style="position: relative;">
                                        <img src="/images/Abdullah.jpg" width="50px;hight:50px" alt="User Photo" />
                                    </div>
                                    <p class="" style="color: #0056b3;font-weight:bolder">Abdullah</p>
                                </div>
                                <div @click="AddMember(2)" class="user-details" style="display:inline-block">
                                    <div v-if="AddGroup.Members.includes(2)"
                                        :style="(AddGroup.Members.includes(2)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>
                                        </h2>
                                    </div>
                                    <img src="/images/Abdullah.jpg" width="50px;hight:50px" alt="User Photo" />
                                    <p class="" style="color: #0056b3; font-weight:bolder">Abdullah</p>
                                </div>
                                <div @click="AddMember(3)" class="user-details" style="display:inline-block">

                                    <div v-if="AddGroup.Members.includes(3)"
                                        :style="(AddGroup.Members.includes(3)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>
                                        </h2>
                                    </div>
                                    <img src="/images/Abdullah.jpg" width="50px;hight:50px" alt="User Photo" />
                                    <p style="color: #0056b3; font-weight:bolder">Abdullah</p>
                                </div>
                            </div>
                            <div class="user-details" style="display:inline-block">
                                <button @click="AddNewGroup" class="btn btn-primary rounded-pill">Add</button>
                            </div>
                        </div>

                    </div>
                    <div>

                    </div>
                    <div class="custom-menu" v-if="showMenu" :style="{ top: `${menuY}px`, left: `${menuX}px` }">
                        <div class="menu-item">
                            <div class="menu-item-content">
                                <div class="menu-item-text">Abdullah</div>
                                <div class="menu-item-image">
                                    <img v-if="user.id != message.user_id"
                                        src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-6.webp" alt="avatar"
                                        class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="30">
                                </div>
                            </div>
                        </div>
                        <div class="menu-item">
                            <div class="memebergroups" style="text-align: center;color:#0056b3">
                                <h6>Members</h6>
                            </div>
                            <div class="menu-item-content">
                                <div class="menu-item-text">Hamdy</div>
                                <div class="menu-item-image">
                                    <img v-if="user.id != message.user_id"
                                        src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-6.webp" alt="avatar"
                                        class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="30">
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-6 col-lg-7 col-xl-7" v-if="activeGroup != null">

                        <ul class="list-unstyled text-white">
                            <!-- sender -->
                            <li class="d-flex justify-content-between mb-4" v-for="(message, index) in groupMessages"
                                :key="index">
                                <img v-if="user.id != message.user_id"
                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-6.webp" alt="avatar"
                                    class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60">
                                <div class="card mask-custom">
                                    <div class="card-header d-flex justify-content-between p-3"
                                        style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">{{ message.user.name }}</p>
                                        <p class="text-light small mb-0">
                                            <i class="far fa-clock"></i> 12 mins ago
                                        </p>
                                    </div>
                                    <div class="card-body"  :style="(message.attachment) ? { 'height': '120px' } : ''">
                                        <p class="mb-1 mt-0 text-center">
                                            {{ message.body }}
                                        </p>
                                        <div class="image-container rounded" v-if="message.attachment != null"

                                        style="width:200px;height: 200px;">
                                        <img :src="message.attachment" alt="" style="width: 100%;">
                                    </div>
                                    </div>

                                </div>
                            </li>
                            <div v-if="activeGroup">
                                <li class="mb-3">
                                    <div class="form-outline form-white">
                                        <textarea class="form-control" id="textAreaExample3" v-model="message"
                                            rows="4"></textarea>
                                        <label class="form-label" for="textAreaExample3">Message</label>
                                    </div>
                                    <div class="form-outline form-white">
                                        <input type="file" id="attachment-input" ref="attachmentInput"
                                            style="display: none;" @change="handleFileSelection">
                                        <button id="attachment-button" @click="openFilePicker">Attach File</button>
                                        <label class="form-label" for="attachment-input">Attachment</label>
                                    </div>
                                    <div lass="image-container" v-if="localImageCreated != null"
                                        style="width:100px;height: 100px;">
                                        <img :src="localImageCreated" alt="" style="width: 100%;">
                                        <span class="remove-icon" @click="removeImage">&#10006;</span>
                                    </div>
                                </li>
                                <button @click="sendMessage" type="button"
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
    props: ['user'],
    data() {
        return {
            groups: [],
            activeGroup: '',
            groupMessages: [],
            message: '',
            showDropdown: false,
            AddGroup: {
                GroupName: '',
                Members: [],
            },
            attachment: '',
            localImageCreated: null,
            ImageName: null,
            newgroupId: '',
            notification: [],
            currentPage: 1,
            lastPage: 1,
            isFetchingData: false,
            isAllDataFetched: false,
            showMenu: false,
            menuX: 0,
            menuY: 0


        }
    },
    mounted() {
        this.getUnreadNotifications();


    },
    methods: {
        openFilePicker() {
            this.$refs.attachmentInput.click();
        },
        handleFileSelection(event) {
            // const conversationId = this.activeGroup;
            this.attachment = ''
            const file = event.target.files[0];
            this.ImageName = file.name;
            console.log('the file from is' + file)
            const formData = new FormData();
            formData.append('image', file);
            axios.post(`/create-local-image`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.localImageCreated = response.data.url;
                this.attachment = response.data.url;
                console.log(response)
            }).catch(err => {
                console.log(err)
            })
        },
        removeImage() {
            //HandleRemoveImage
            const image = this.ImageName
            axios.post(`/delete-local-image`, { 'image': image }).then(response => {

            }).then(response => {
                console.log(response)
            }).catch(err => {
                console.log(err)
            })

        },
        handleRightClick(event) {
            event.preventDefault();
            const buttonRect = event.target.getBoundingClientRect();
            this.showMenu = !this.showMenu;
            this.menuX = buttonRect.left;
            this.menuY = buttonRect.bottom;
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
            const attachment = this.attachment
            console.log('the attachment from send message is' + attachment)
            if (!this.message) {
                return alert('please Enter Message');
            }
            const formData = new FormData();
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

                this.message = null;

            });
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
                this.showDropdown = false;
                this.$fire({
                    title: `${response.data.groups.name}`, text: "has been Created Successfully", type: "success", timer: 2000
                });
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
                console.log(e.conversation.id)
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
</style>
