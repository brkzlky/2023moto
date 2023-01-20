var msg = new Vue({
    el: '#messages',
    data: {
      messages: [],
      messagesBackup: [],
      selectedMessage: null,
      messageInfo: null,
      newMessage: null,
      disableSend: false,
      alertMsg: null,
      ucg: null,
      userguid: $('#mgd').val(),
      lang: $('html')[0].lang,
      csrf: $('meta[name="csrf-token"]').attr('content'),
      deleteMsgText: $('#delete_msg').val(),
      monthNames: ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ],
      dropToggleTriggered: false,
    },
    methods: {
      getMessages: function() {
        var postdata = new FormData();
        postdata.append('user', this.userguid);
        axios.post(window.location.origin+'/member/messages', postdata).then((d) => {
          if(d.status == 200) {
            this.messages = [];
            this.messages = d.data.messages;
            this.messagesBackup = d.data.messages;
          }
        })
      },
      openMessage: function(guid) {
        if(this.ucg != guid) {
          this.ucg = guid;
        }
        if(this.selectedMessage != guid) {
          this.selectedMessage = guid;
          var postdata = new FormData();
          postdata.append('user', this.userguid);
          postdata.append('chat', guid);
          axios.post(window.location.origin+'/member/messages-detail', postdata).then((d) => {
              if(d.status == 200) {
                  this.messageInfo = d.data.chatinfo;

                  setTimeout(() => {
                    if(!this.dropToggleTriggered) {
                      $('.c-msg__more.dropdown-toggle').on('click', function (event) {
                        if ($(this).hasClass('is-open')) {
                          $(this).removeClass('is-open');
                        } else {
                          $('.c-msg__more').removeClass('is-open');
                          $(this).addClass('is-open');
                          $('body').removeClass('menu-isOpen');
                          $('.c-hamburger').removeClass('c-hamburger--isActive');
                        }
                      });
                      this.dropToggleTriggered = true;
                    }
                    
                    msg.scrollMessages();
                  }, 250);
              }
          })
        }

        setTimeout(() => {
          $('.c-msg__right').removeClass('is-hidden');
          $('.c-msg__left').addClass('is-hidden');
        }, 250);
      },
      searchMessage: function(e) {
        var key = e.target.value;
        this.messages = this.messagesBackup;
        if(this.lang != 'ar') {
          var filter = this.messages.filter((m) => m.listing_info.name_en.toLowerCase().includes(key.toLowerCase()));
        } else {
          var filter = this.messages.filter((m) => m.listing_info.name_ar.toLowerCase().includes(key.toLowerCase()));
        }
        
        this.messages = filter;
      },
      closeMessageInfo: function() {
        setTimeout(() => {
          $('.c-msg__right').addClass('is-hidden');
          $('.c-msg__left').removeClass('is-hidden');
        }, 250);
      },
      sendMessage: function(message) {
        if(this.newMessage == null) {
          this.alertMsg = 'You have to type a message';
          this.showAlert('warning');
        } else {
          if(this.newMessage == '' || this.newMessage == 'null' || this.newMessage.trim() == '') {
            this.alertMsg = 'You cannot send an empty message';
            this.showAlert('warning');
          } else {
            var postdata = new FormData();
            postdata.append('user', this.userguid);
            postdata.append('chat', message.owner.chatguid);
            postdata.append('msg', this.newMessage);
            axios.post(window.location.origin+'/member/send-message', postdata).then((d) => {
              if(d.status == 200) {
                if(d.data.result == 200) {
                  this.alertMsg = d.data.msg;
                  this.showAlert('success');
                  this.messageInfo.messages.push(d.data.message);
                  this.newMessage = null;

                  this.scrollMessages();
                } else {
                  this.alertMsg = d.data.msg;
                  this.showAlert('error');
                }
              } else {
                this.alertMsg = 'An error occur. Please try later';
                this.showAlert('error');
              }
            })
          }
        }
      },
      deleteMessage: function(msg) {
        this.deleteMsgText = $('#delete_msg').val();
        var username = msg.owner.name;
        this.deleteMsgText = this.deleteMsgText.split(':user').join(username);

        setTimeout(() => {
          $('#deleteMsgModal').modal('show');
        }, 100);
      },
      deleteApproved: function() {
        $('#deleteMsgModal').modal('hide');
        var postdata = new FormData();
        postdata.append('user', this.userguid);
        postdata.append('chat', this.ucg);
        axios.post(window.location.origin+'/member/delete-message', postdata).then((d) => {
          if(d.status == 200) {
            if(d.data.result == 200) {
              this.alertMsg = d.data.msg;
              this.showAlert('success');
              this.messageInfo = null;
              this.selectedMessage = null;
              this.getMessages();
              this.dropToggleTriggered = false;

            } else {
              this.alertMsg = d.data.msg;
              this.showAlert('error');
            }
          } else {
            this.alertMsg = 'An error occur. Please try later';
            this.showAlert('error');
          }
        })
      },
      blockUser: function(msg) {
        var postdata = new FormData();
        postdata.append('user', this.userguid);
        postdata.append('opposite', msg.owner.user_guid);
        axios.post(window.location.origin+'/member/block-user', postdata).then((d) => {
          if(d.status == 200) {
            if(d.data.result == 200) {
              this.alertMsg = d.data.msg;
              this.showAlert('success');

              this.messageInfo.status.blocked_by = 'you';
              this.messageInfo.status.blocked = 'yes';
            } else {
              this.alertMsg = d.data.msg;
              this.showAlert('error');
            }
          } else {
            this.alertMsg = 'An error occur. Please try later';
            this.showAlert('error');
          }
        });
      },
      unblockUser: function(msg) {
        var postdata = new FormData();
        postdata.append('user', this.userguid);
        postdata.append('opposite', msg.owner.user_guid);
        axios.post(window.location.origin+'/member/unblock-user', postdata).then((d) => {
          if(d.status == 200) {
            if(d.data.result == 200) {
              this.alertMsg = d.data.msg;
              this.showAlert('success');

              this.messageInfo.status.blocked_by = null;
              this.messageInfo.status.blocked = 'no';
            } else {
              this.alertMsg = d.data.msg;
              this.showAlert('error');
            }
          } else {
            this.alertMsg = 'An error occur. Please try later';
            this.showAlert('error');
          }
        });
      },
      userProfile: function(user) {
        window.open(user.url, '_blank');
      },
      getMsgDate: function(date) {
        var d = new Date(date);
            month = this.monthNames[d.getMonth()];
            day = '' + d.getDate();
            year = d.getFullYear();

        if (day.length < 2) day = '0' + day;

        return [month+' '+day, year].join(', ');
      },
      getMsgTime: function(date) {
        var d = new Date(date);
        if (d.getHours()>=12){
            var hour = parseInt(d.getHours()) - 12;
            if(hour == 0){
              hour = 12;
            }
            var amPm = "PM";
        } else {
            var hour = d.getHours();
            var amPm = "AM";
        }
        if(d.getMinutes() < 10){
          min = '0'+d.getMinutes();
        } else {
          min = d.getMinutes();
        }
        var time = hour + ":" + min + " " + amPm;
        return time;
      },
      getMsgDateTime: function(date) {
        var d = new Date(date);
        month = this.monthNames[d.getMonth()];
        day = '' + d.getDate();
        year = d.getFullYear();

        if (day.length < 2) day = '0' + day;

        if (d.getHours()>=12){
            var hour = parseInt(d.getHours()) - 12;
            if(hour == 0){
              hour = 12;
            }
            var amPm = "PM";
        } else {
            var hour = d.getHours();
            var amPm = "AM";
        }
        if(d.getMinutes() < 10){
          min = '0'+d.getMinutes();
        } else {
          min = d.getMinutes();
        }
        var time = [month+' '+day, year].join(', ')+' '+hour + ":" + min + " " + amPm;
        return time;
      },
      scrollMessages: function() {
        if ($('.c-msg__right-body').length !== 0) {
          var msgRightHeight = $('.c-msg__right-body')[0].scrollHeight;
          $('.c-msg__right-body').animate({ scrollTop: msgRightHeight }, 450);
        }
      },
      showAlert: function(type) {
        $('.alertbox').addClass(type);
          setTimeout(() => {
            $('.alertbox').removeClass(type);
            setTimeout(() => {
              this.alertMsg = null;
            }, 500);
          }, 4000);
      }
    },
    mounted() {
      setTimeout(() => {
        msg.getMessages();
      }, 200);

      $('#deleteMsgModal').on('hidden.bs.modal', function(){
        this.deleteMsgText = $('#delete_msg').val();
        this.ucg = null;
    });
    }
});