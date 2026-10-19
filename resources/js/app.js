import './bootstrap';

import Alpine from 'alpinejs';
import axios from "axios";
window.Alpine = Alpine;

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

Alpine.data("storefront", () => ({
    mobileMenu: false,
    cartDrawer: false,
    searchOpen: false,
    quickView: null,
    filtersOpen: false,
    toast: null,
    openQuickView(product) {
        this.quickView = product;
    },
    addToCart(name) {
        this.toast = `${name} was added to your bag.`;
        window.setTimeout(() => (this.toast = null), 3000);
    },
}));


Alpine.data("heroSlider", () => ({
    activeSlide: 0,
    slideCount: 3,
    timer: null,
    paused: false,

    init() {
        this.startTimer();
    },

    destroy() {
        this.stopTimer();
    },

    startTimer() {
        this.stopTimer();

        if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            return;
        }

        this.timer = window.setInterval(() => {
            if (!this.paused) {
                this.next();
            }
        }, 5000);
    },

    stopTimer() {
        if (this.timer !== null) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    },

    pause() {
        this.paused = true;
    },

    resume() {
        this.paused = false;
    },

    next() {
        this.activeSlide = (this.activeSlide + 1) % this.slideCount;
    },

    previous() {
        this.activeSlide =
            (this.activeSlide - 1 + this.slideCount) % this.slideCount;
    },

    goTo(index) {
        this.activeSlide = index;
    },
}));

Alpine.data("quantity", (initial = 1) => ({
    value: initial,
    increment() {
        this.value++;
    },
    decrement() {
        this.value = Math.max(1, this.value - 1);
    },
}));

Alpine.start();
