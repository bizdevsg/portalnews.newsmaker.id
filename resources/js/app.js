import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
import "@fortawesome/fontawesome-free/css/all.css";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

Livewire.start();

import "./bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    const shouldSkipDoubleSubmit = (form) => {
        if (!form || form.hasAttribute("data-no-double-submit")) {
            return true;
        }

        const method = (form.getAttribute("method") || "get").toLowerCase();
        if (method === "get") {
            return true;
        }

        return Array.from(form.attributes).some(({ name }) => {
            return (
                name.startsWith("wire:submit") ||
                name.startsWith("x-on:submit") ||
                name === "@submit"
            );
        });
    };

    const getFormSubmitters = (form) => {
        const submitters = new Set(
            form.querySelectorAll(
                'button[type="submit"], input[type="submit"], button:not([type])'
            )
        );

        if (!form.id) {
            return Array.from(submitters);
        }

        const escapedId = window.CSS?.escape ? CSS.escape(form.id) : form.id;
        document.querySelectorAll(`[form="${escapedId}"]`).forEach((element) => {
            const tagName = element.tagName.toLowerCase();
            const type = (
                element.getAttribute("type") ||
                (tagName === "button" ? "submit" : "")
            ).toLowerCase();

            if (type === "submit") {
                submitters.add(element);
            }
        });

        return Array.from(submitters);
    };

    const setSubmitterLoadingState = (submitter) => {
        if (!submitter) {
            return;
        }

        if (
            submitter instanceof HTMLInputElement &&
            !("originalSubmitLabel" in submitter.dataset)
        ) {
            submitter.dataset.originalSubmitLabel = submitter.value;
            submitter.value = "Memproses...";
        }

        if (
            submitter instanceof HTMLButtonElement &&
            !("originalSubmitLabel" in submitter.dataset)
        ) {
            submitter.dataset.originalSubmitLabel = submitter.innerHTML;
            submitter.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        }
    };

    const resetSubmitterState = (submitter) => {
        if (!submitter) {
            return;
        }

        if (
            submitter instanceof HTMLInputElement &&
            "originalSubmitLabel" in submitter.dataset
        ) {
            submitter.value = submitter.dataset.originalSubmitLabel;
            delete submitter.dataset.originalSubmitLabel;
        }

        if (
            submitter instanceof HTMLButtonElement &&
            "originalSubmitLabel" in submitter.dataset
        ) {
            submitter.innerHTML = submitter.dataset.originalSubmitLabel;
            delete submitter.dataset.originalSubmitLabel;
        }

        submitter.disabled = false;
        submitter.removeAttribute("aria-disabled");
    };

    const unlockForm = (form) => {
        delete form.dataset.submitting;
        form.removeAttribute("aria-busy");

        getFormSubmitters(form).forEach((submitter) => {
            resetSubmitterState(submitter);
        });
    };

    const lockForm = (form, submitter) => {
        form.dataset.submitting = "true";
        form.setAttribute("aria-busy", "true");

        getFormSubmitters(form).forEach((element) => {
            element.disabled = true;
            element.setAttribute("aria-disabled", "true");
        });

        setSubmitterLoadingState(submitter);
    };

    document.addEventListener("submit", (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || shouldSkipDoubleSubmit(form)) {
            return;
        }

        if (form.dataset.submitting === "true") {
            event.preventDefault();
            return;
        }

        if (event.defaultPrevented) {
            return;
        }

        lockForm(form, event.submitter || null);
    });

    window.addEventListener("pageshow", () => {
        document
            .querySelectorAll("form[data-submitting='true']")
            .forEach((form) => {
                unlockForm(form);
            });
    });

    // Light switcher
    const lightSwitches = document.querySelectorAll(".light-switch");
    if (lightSwitches.length > 0) {
        lightSwitches.forEach((lightSwitch, i) => {
            if (localStorage.getItem("dark-mode") === "true") {
                lightSwitch.checked = true;
            }
            lightSwitch.addEventListener("change", () => {
                const { checked } = lightSwitch;
                lightSwitches.forEach((el, n) => {
                    if (n !== i) {
                        el.checked = checked;
                    }
                });
                document.documentElement.classList.add("**:transition-none!");
                if (lightSwitch.checked) {
                    document.documentElement.classList.add("dark");
                    document.querySelector("html").style.colorScheme = "dark";
                    localStorage.setItem("dark-mode", true);
                    document.dispatchEvent(
                        new CustomEvent("darkMode", { detail: { mode: "on" } })
                    );
                } else {
                    document.documentElement.classList.remove("dark");
                    document.querySelector("html").style.colorScheme = "light";
                    localStorage.setItem("dark-mode", false);
                    document.dispatchEvent(
                        new CustomEvent("darkMode", { detail: { mode: "off" } })
                    );
                }
                setTimeout(() => {
                    document.documentElement.classList.remove(
                        "**:transition-none!"
                    );
                }, 1);
            });
        });
    }
    const datepickers = document.querySelectorAll(".datepicker");
    if (datepickers.length > 0) {
        import("flatpickr").then(({ default: flatpickr }) => {
            flatpickr(".datepicker", {
                mode: "range",
                static: true,
                monthSelectorType: "static",
                dateFormat: "M j, Y",
                defaultDate: [
                    new Date().setDate(new Date().getDate() - 6),
                    new Date(),
                ],
                prevArrow:
                    '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
                nextArrow:
                    '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
                onReady: (selectedDates, dateStr, instance) => {
                    // eslint-disable-next-line no-param-reassign
                    instance.element.value = dateStr.replace("to", "-");
                    const customClass =
                        instance.element.getAttribute("data-class");
                    if (customClass) {
                        instance.calendarContainer.classList.add(customClass);
                    }
                },
                onChange: (selectedDates, dateStr, instance) => {
                    // eslint-disable-next-line no-param-reassign
                    instance.element.value = dateStr.replace("to", "-");
                },
            });
        });
    }
});
