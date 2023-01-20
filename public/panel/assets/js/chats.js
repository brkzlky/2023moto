import { chatModal } from "./userChatModal.js";
import { sendHttpRequest } from "./sendRequest.js";

const show_user_chat_modal = document.getElementsByClassName(
    "show_user_chat_modal"
);

const data = [];
let isBusy = false;
Array.from(show_user_chat_modal).forEach((user_chat) => {
    user_chat.addEventListener("click", async (e) => {
        if (isBusy) {
            return;
        }
        const user_chat_guid = user_chat.getAttribute("user_chat_guid");
        const chatExist = document.getElementById("chat" + user_chat_guid);

        if (chatExist) {
            chatExist.style.border = "1px solid #1bc5bd";
            setTimeout(() => {
                chatExist.style.border = "none";
            }, 300);
            KTUtil.btnRelease(user_chat);
            user_chat.innerHTML = `<i class="flaticon-eye"></i>`;
            return;
        }

        const user_opposite_guid = user_chat.getAttribute("user_opposite_guid");
        const modal = document.getElementsByClassName(
            "modal-sticky-bottom-right"
        );
        user_chat.innerHTML = "";
        KTUtil.btnWait(user_chat, "spinner spinner-center spinner-white");
        isBusy = true;
        await sendHttpRequest("POST", Routes.chats, {
            user_chat_guid,
            user_guid: user_opposite_guid,
        })
            .then((response) => {
                const res = JSON.parse(response);
                chatModal(user_chat_guid, res,user_opposite_guid).then((e) => {
                    const close = document.getElementById(
                        "close" + user_chat_guid
                    );
                    const content =
                        document.getElementsByClassName("scroll-pull");
                    content[0].scrollTop = content[0].scrollHeight;
                    close.addEventListener("click", (e) => {
                        const chatExist = document.getElementById(
                            "chat" + user_chat_guid
                        );
                        chatExist.remove();
                    });
                });
            })
            .finally(() => {
                isBusy = false;
                KTUtil.btnRelease(user_chat);
                user_chat.innerHTML = `<i class="flaticon-eye"></i>`;
            });
    });
});
