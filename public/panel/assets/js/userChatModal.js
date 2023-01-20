export function chatModal(user_chat_guid, res,user_opposite_guid) {
    return new Promise((resolve) => {
        var pop = document.createElement("div");
        const modalHeader = `
        <div class="modal" id="chat${user_chat_guid}" role="dialog" data-backdrop="false" aria-modal="true" style="padding-right: 17px; display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!--begin::Card-->
                <div class="card card-custom">
                    <!--begin::Header-->
                    <div class="card-header align-items-center px-4 py-3">
                        <div class="text-left flex-grow-1">
                            <!--begin::Dropdown Menu-->

                            <!--end::Dropdown Menu-->
                        </div>
                        <div class="text-center flex-grow-1">
                            <div class="text-dark-75 font-weight-bold font-size-h5">
                                ${res.chat.user_info.name}
                            </div>
                        </div>
                        <div class="text-right flex-grow-1">
                            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" id="close${user_chat_guid}" data-dismiss="modal">
                                <i class="ki ki-close icon-1x"></i>
                            </button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Scroll-->
                        <div class="scroll scroll-pull" data-height="375" data-mobile-height="300" style="overflow: auto; height: 300px;">
                            <!--begin::Messages-->
                            <div class="messages">`;

        const modalFooter = `
                            </div>
                            <!--end::Messages-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    `;
        let modalContent = "";
        Array.from(res.chat.messages_info).forEach((message) => {
            if (message.user_own_guid != user_opposite_guid) {
                modalContent += `<!--begin::Message Out-->
            <div class="d-flex flex-column mb-5 align-items-end">
                <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                    ${message.message}
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <span class="text-muted font-size-sm">${message.send_time}</span>
                    </div>
                </div>
            </div>
            <!--end::Message Out-->`;
            } else {
                modalContent += ` <!--begin::Message In-->
            <div class="d-flex flex-column mb-5 align-items-start">
                <div class="d-flex align-items-center">
                    <div>
                        <span class="text-muted font-size-sm"></span>
                    </div>
                </div>
                <div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
                ${message.message}
                </div>
                <div class="d-flex align-items-center ml-1">
                    <div>
                    <span class="text-muted font-size-sm">${message.send_time}</span>
                    </div>
                </div>
            </div>
            <!--end::Message In-->`;
            }
        });
        pop.innerHTML = modalHeader + modalContent + modalFooter;
        document.body.appendChild(pop);
        resolve();
    });
}
