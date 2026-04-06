const GLOBAL_CONFIRMATION_MODAL_ID = "global-confirmation-modal";
const BACK_LABEL_PATTERN = /\b(kembali|back)\b/i;
const DELETE_LABEL_PATTERN = /\b(hapus|delete|remove|trash)\b/i;

const isElement = (value) => value instanceof Element;

const getControlLabel = (control) => {
    if (!isElement(control)) {
        return "";
    }

    const label =
        control.dataset.confirmLabel ||
        control.getAttribute("aria-label") ||
        control.getAttribute("title") ||
        control.value ||
        control.textContent ||
        "";

    return label.replace(/\s+/g, " ").trim();
};

const hasModalAncestor = (element) => {
    if (!isElement(element)) {
        return false;
    }

    const modalAncestor = element.closest(
        "[data-modal], [role='dialog'], [id*='modal'], [id*='Modal']"
    );

    return Boolean(
        modalAncestor && !modalAncestor.hasAttribute("data-confirm-modal")
    );
};

const getEditableFields = (form) => {
    if (!form) {
        return [];
    }

    return Array.from(
        form.querySelectorAll("input, textarea, select")
    ).filter((field) => {
        if (!(field instanceof HTMLElement) || field.disabled) {
            return false;
        }

        if (field.matches("input")) {
            const type = (field.getAttribute("type") || "text").toLowerCase();

            if (
                [
                    "hidden",
                    "submit",
                    "button",
                    "reset",
                    "image",
                ].includes(type)
            ) {
                return false;
            }
        }

        return !field.hasAttribute("readonly");
    });
};

const isLogoutForm = (form) => {
    if (!form) {
        return false;
    }

    const action = (form.getAttribute("action") || "").toLowerCase();
    return /\/logout\/?$/.test(action);
};

const getPrimaryEditableForm = () => {
    const forms = Array.from(document.querySelectorAll("form")).filter(
        (form) =>
            !isLogoutForm(form) &&
            form.method.toLowerCase() !== "get" &&
            getEditableFields(form).length > 0
    );

    return forms.length === 1 ? forms[0] : null;
};

const resolveConfirmButtonClasses = (variant) => {
    const baseClasses =
        "inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900";

    if (variant === "danger") {
        return `${baseClasses} bg-red-600 hover:bg-red-700 focus:ring-red-500`;
    }

    return `${baseClasses} bg-blue-600 hover:bg-blue-700 focus:ring-blue-500`;
};

const buildSubmitMessage = (label) => {
    if (/\b(simpan|save|update|ubah|edit)\b/i.test(label)) {
        return "Apakah Anda yakin ingin menyimpan perubahan pada form ini?";
    }

    if (/\b(tambah|buat|create|submit|kirim)\b/i.test(label)) {
        return "Apakah Anda yakin ingin mengirim data form ini?";
    }

    return "Apakah Anda yakin ingin melanjutkan proses submit form ini?";
};

const createConfirmationModal = () => {
    const wrapper = document.createElement("div");
    wrapper.innerHTML = `
        <div id="${GLOBAL_CONFIRMATION_MODAL_ID}" data-confirm-modal class="fixed inset-0 z-[250] hidden items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <div class="space-y-3">
                    <p data-confirm-title class="text-lg font-semibold text-slate-900 dark:text-slate-100"></p>
                    <p data-confirm-message class="text-sm leading-6 text-slate-600 dark:text-slate-300"></p>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-confirm-cancel class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">
                        Batal
                    </button>
                    <button type="button" data-confirm-approve>
                        Ya
                    </button>
                </div>
            </div>
        </div>
    `.trim();

    const modal = wrapper.firstElementChild;
    document.body.appendChild(modal);

    return {
        root: modal,
        title: modal.querySelector("[data-confirm-title]"),
        message: modal.querySelector("[data-confirm-message]"),
        cancel: modal.querySelector("[data-confirm-cancel]"),
        approve: modal.querySelector("[data-confirm-approve]"),
    };
};

const requestSubmitSafely = (form, submitter) => {
    if (!form) {
        return;
    }

    if (typeof form.requestSubmit === "function") {
        form.requestSubmit(submitter);
        return;
    }

    HTMLFormElement.prototype.submit.call(form);
};

export default function initConfirmationGuard() {
    if (window.__confirmationGuardInitialized) {
        return;
    }

    window.__confirmationGuardInitialized = true;

    const modal = createConfirmationModal();
    let activeAction = null;
    let previousFocusedElement = null;

    const closeModal = () => {
        modal.root.classList.add("hidden");
        modal.root.classList.remove("flex");
        document.body.classList.remove("overflow-hidden");
        activeAction = null;

        if (previousFocusedElement instanceof HTMLElement) {
            previousFocusedElement.focus();
        }
    };

    const openModal = ({ title, message, confirmLabel, variant, onConfirm }) => {
        previousFocusedElement = document.activeElement;
        activeAction = onConfirm;
        modal.title.textContent = title;
        modal.message.textContent = message;
        modal.approve.textContent = confirmLabel;
        modal.approve.className = resolveConfirmButtonClasses(variant);
        modal.root.classList.remove("hidden");
        modal.root.classList.add("flex");
        document.body.classList.add("overflow-hidden");
        modal.approve.focus();
    };

    modal.cancel.addEventListener("click", closeModal);

    modal.approve.addEventListener("click", () => {
        const confirmAction = activeAction;
        closeModal();
        confirmAction?.();
    });

    modal.root.addEventListener("click", (event) => {
        if (event.target === modal.root) {
            closeModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !modal.root.classList.contains("hidden")) {
            closeModal();
        }
    });

    document.addEventListener(
        "click",
        (event) => {
            const control = event.target.closest(
                "a, button, input[type='submit'], input[type='button']"
            );

            if (!isElement(control) || control.closest("[data-confirm-modal]")) {
                return;
            }

            if (hasModalAncestor(control)) {
                return;
            }

            const logoutForm = control.closest("form");
            if (logoutForm && isLogoutForm(logoutForm)) {
                event.preventDefault();
                event.stopPropagation();

                openModal({
                    title: "Konfirmasi Logout",
                    message: "Apakah Anda yakin ingin logout dari sesi ini?",
                    confirmLabel: "Ya, Logout",
                    variant: "danger",
                    onConfirm: () => requestSubmitSafely(logoutForm),
                });
                return;
            }

            const form =
                control.form instanceof HTMLFormElement
                    ? control.form
                    : control.closest("form");

            const isSubmitControl = control.matches(
                "button[type='submit'], input[type='submit']"
            );
            const submitLabel = getControlLabel(control);

            if (
                isSubmitControl &&
                form &&
                !isLogoutForm(form) &&
                form.method.toLowerCase() !== "get" &&
                !form.hasAttribute("data-confirm-skip-submit") &&
                !control.hasAttribute("data-confirm-skip-submit") &&
                !DELETE_LABEL_PATTERN.test(submitLabel) &&
                getEditableFields(form).length > 0
            ) {
                event.preventDefault();
                event.stopPropagation();

                openModal({
                    title: "Konfirmasi Submit",
                    message: buildSubmitMessage(submitLabel),
                    confirmLabel: submitLabel
                        ? `Ya, ${submitLabel}`
                        : "Ya, Submit",
                    variant: "primary",
                    onConfirm: () => requestSubmitSafely(form, control),
                });
                return;
            }

            const controlLabel = getControlLabel(control);
            const isBackControl =
                BACK_LABEL_PATTERN.test(controlLabel) &&
                !control.hasAttribute("data-confirm-skip-back");

            if (!isBackControl) {
                return;
            }

            const hasEditableFormContext = Boolean(
                control.closest("form") || getPrimaryEditableForm()
            );
            const href =
                control.tagName === "A" ? control.getAttribute("href") : null;

            if (
                !hasEditableFormContext ||
                (!href && !control.dataset.confirmBackUrl)
            ) {
                return;
            }

            if (
                control.tagName === "BUTTON" &&
                (control.type || "").toLowerCase() === "submit"
            ) {
                return;
            }

            const onclickValue = control.getAttribute("onclick") || "";
            if (/modal/i.test(onclickValue)) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            openModal({
                title: "Konfirmasi Kembali",
                message:
                    "Apakah Anda yakin ingin kembali? Perubahan pada form yang belum disimpan akan hilang.",
                confirmLabel: "Ya, Kembali",
                variant: "danger",
                onConfirm: () => {
                    const backUrl = control.dataset.confirmBackUrl || href;

                    if (backUrl && backUrl !== "#") {
                        window.location.assign(backUrl);
                    }
                },
            });
        },
        true
    );
}
