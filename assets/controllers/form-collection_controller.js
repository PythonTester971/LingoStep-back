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
    console.log('Adding new element');

    const prototype = this.prototypeValue;
    const index = this.indexValue || 0;

    const newForm = prototype.replace(/__name__/g, index);
    const element = document.createElement('li');
    element.innerHTML = newForm;
    element.classList.add('mb-3', 'border', 'p-3');

    this.collectionContainerTarget.appendChild(element);
    this.indexValue = index + 1;

    console.log(`Added element with index ${index}`);
  }

  removeCollectionElement(event) {
    event.preventDefault();
    const items = this.collectionContainerTarget.children;
    if (items.length > 0) {
      items[items.length - 1].remove();
    }
  }

}