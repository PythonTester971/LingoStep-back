// assets/controllers/form-collection_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ["collectionContainer"]

  static values = {
    index: Number,
    prototype: String,
  }

  connect() {
    console.log("Form collection controller connected");
    console.log("Initial index:", this.hasIndexValue ? this.indexValue : 0);
  }

  addCollectionElement(event) {
    event.preventDefault();

    if (!this.hasPrototypeTarget) return;

    const prototypeHTML = this.prototypeTarget.innerHTML;
    const index = this.indexValue || 0;
    const newFormHTML = prototypeHTML.replace(/__name__/g, index);

    const li = document.createElement("li");
    li.classList.add("mb-3", "border", "p-3");
    li.innerHTML = newFormHTML;

    this.collectionContainerTarget.appendChild(li);
    this.indexValue = index + 1;

    console.log(`Added question ${index}`);
  }

  removeCollectionElement(event) {
    event.preventDefault();
    const items = this.collectionContainerTarget.children;
    if (items.length > 0) {
      items[items.length - 1].remove();
    }
  }

}