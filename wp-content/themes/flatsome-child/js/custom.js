document.addEventListener("DOMContentLoaded", function () {
    if (!customElements.get("wc-order-attribution-inputs")) {
      class MyCustomElement extends HTMLElement {
        connectedCallback() {
          // logic cho component của bạn ở đây
          this.innerHTML = "My custom input loaded!";
        }
      }
      customElements.define("wc-order-attribution-inputs", MyCustomElement);
    }
  });