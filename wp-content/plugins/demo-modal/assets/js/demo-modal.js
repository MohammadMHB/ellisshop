jQuery(document).ready(function ($) {
  class VisitModal {
    static getCookieName() {
      const subfolder = window.location.pathname.split("/")[1];
      return `abzarwp_demo_modal_${subfolder}`;
    }

    constructor(showEvery) {
      this.showEvery = showEvery;
      this.cookieName = VisitModal.getCookieName();
      this.visits = this.getCookie(this.cookieName)
        ? parseInt(this.getCookie(this.cookieName))
        : 0;
    }

    getCookie(name) {
      let match = document.cookie.match(
        new RegExp("(^| )" + name + "=([^;]+)")
      );
      return match ? match[2] : null;
    }

    setCookie(name, value, days, path = "/") {
      const d = new Date();
      d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
      const expires = "expires=" + d.toUTCString();
      document.cookie = `${name}=${value};${expires};path=${path}`;
    }

    incrementVisits() {
      this.visits++;
      const path = `/${window.location.pathname.split("/")[1]}`;
      this.setCookie(this.cookieName, this.visits, 365, path);
    }

    isSpecialPage() {
      return (
        $("body").hasClass("woocommerce-cart") ||
        $("body").hasClass("woocommerce-checkout") ||
        $("body").hasClass("woocommerce-order-received")
      );
    }

    checkAndShowModal() {
      if (
        this.isSpecialPage() ||
        this.visits === 1 ||
        (this.visits - 1) % this.showEvery === 0
      ) {
        this.showModal();
      }
    }

    showModal() {
      let adminGuide = null;
      if (modal_object.isAdmin) {
        adminGuide = modal_object.adminText;
      }

      return Swal.fire({
        title: modal_object.modalTitle,
        text: modal_object.modalText,
        icon: "warning",
        footer: adminGuide,
        confirmButtonText: modal_object.confirmButtonText,
      });
    }
    run() {
      if (!this.isSpecialPage()) {
        this.incrementVisits();
      }
      this.checkAndShowModal();
    }
  }

  const visitModal = new VisitModal(3);
  visitModal.run();
  
  $("form.checkout").on("submit", function (e) {
    e.preventDefault();
    window.location.href = window.location.href =
      modal_object.siteUrl + "/custom_wp_die_action";
  });
});
