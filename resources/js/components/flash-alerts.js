const ALERT_SELECTOR = "[data-flash-alert], #successAlert";
const CLOSE_SELECTOR = "[data-flash-alert-close], #closeSuccessAlert";
const VISIBLE_FOR_MS = 2000;
const FADE_DURATION_MS = 300;

const dismissAlert = (alert) => {
    if (!(alert instanceof HTMLElement) || alert.dataset.flashClosing === "true") {
        return;
    }

    alert.dataset.flashClosing = "true";
    alert.style.transition = `opacity ${FADE_DURATION_MS}ms ease, transform ${FADE_DURATION_MS}ms ease`;
    alert.style.opacity = "0";
    alert.style.transform = "translateY(-6px)";
    alert.style.pointerEvents = "none";

    window.setTimeout(() => {
        alert.remove();
    }, FADE_DURATION_MS);
};

export default function initFlashAlerts() {
    const alerts = document.querySelectorAll(ALERT_SELECTOR);

    alerts.forEach((alert) => {
        if (!(alert instanceof HTMLElement) || alert.dataset.flashInitialized === "true") {
            return;
        }

        alert.dataset.flashInitialized = "true";
        alert.style.opacity = "1";
        alert.style.transform = "translateY(0)";

        const closeButton =
            alert.querySelector("[data-flash-alert-close]") ||
            document.getElementById("closeSuccessAlert");

        closeButton?.addEventListener("click", () => dismissAlert(alert));

        window.setTimeout(() => {
            dismissAlert(alert);
        }, VISIBLE_FOR_MS);
    });
}
