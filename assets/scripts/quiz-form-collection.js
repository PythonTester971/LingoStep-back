document.addEventListener('DOMContentLoaded', () => {
  console.log('Quiz form collection initialization started');

  document.querySelectorAll('.add-question-link').forEach(btn => {
    btn.addEventListener("click", addQuestionToCollection);
  });

  // Initialize question indexes for existing questions
  document.querySelectorAll('.question-item').forEach((questionItem, questionIndex) => {
    console.log(`Initializing question ${questionIndex}`);

    // Set the question index attribute for existing questions
    questionItem.setAttribute('data-question-index', questionIndex);

    addQuestionRemoveButton(questionItem);

    // Check for option collection prototype in this question
    const optionsHolder = questionItem.querySelector('.options');
    if (optionsHolder && optionsHolder.dataset.prototype) {
      console.log(`Question ${questionIndex} has option prototype: ${optionsHolder.dataset.prototype.substring(0, 50)}...`);
    }

    const addOptionBtn = questionItem.querySelector('.add-option-link');
    if (addOptionBtn) {
      addOptionBtn.addEventListener('click', event => {
        addOptionToCollection(event, questionItem);
      });
    }

    // Initialize option indexes for existing options and ensure they have proper form names
    const options = questionItem.querySelectorAll('.option-item');
    console.log(`Question ${questionIndex} has ${options.length} options`);

    options.forEach((optionItem, optionIndex) => {
      optionItem.setAttribute('data-option-index', optionIndex);

      // Ensure each input in this option has the correct name attribute format
      // for the specific question and option index
      const inputs = optionItem.querySelectorAll('input, select, textarea');
      inputs.forEach(input => {
        const currentName = input.getAttribute('name');
        if (currentName) {
          // Check if name contains question and option indices
          const questionPattern = new RegExp(`\\[questions\\]\\[(\\d+)\\]`);
          const match = questionPattern.exec(currentName);

          if (match && parseInt(match[1]) !== questionIndex) {
            // Fix incorrect question index in input name
            const newName = currentName.replace(
              /\[questions\]\[\d+\]/,
              `[questions][${questionIndex}]`
            );
            input.setAttribute('name', newName);
          }
        }
      });

      addOptionRemoveButton(optionItem);
    });
  });

  console.log('Quiz form collection initialization completed');
});

function addQuestionToCollection(e) {
  const questionsHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

  const prototype = questionsHolder.dataset.prototype;
  if (!prototype) {
    console.error('Question prototype not found');
    return;
  }

  const index = parseInt(questionsHolder.dataset.index);

  // Create a new form from the prototype
  const newForm = prototype.replace(/__name__/g, index);

  const questionItem = document.createElement('li');
  questionItem.classList.add('question-item', 'card', 'mb-4', 'p-3');
  questionItem.setAttribute('data-question-index', index);

  const cardHeader = document.createElement('div');
  cardHeader.classList.add('card-header', 'd-flex', 'justify-content-between', 'align-items-center', 'bg-light');
  cardHeader.innerHTML = `<h4>Question ${index + 1}</h4>`;

  const cardBody = document.createElement('div');
  cardBody.classList.add('card-body');

  // Insert the form HTML
  cardBody.innerHTML = newForm;

  // Extract any option prototype that might be in the new form
  const optionPrototypeElement = cardBody.querySelector('[data-prototype]');
  if (optionPrototypeElement) {
    console.log('Found option prototype in new question form:', optionPrototypeElement.dataset.prototype);
  }

  questionItem.appendChild(cardHeader);
  questionItem.appendChild(cardBody);

  questionsHolder.appendChild(questionItem);

  // Update the index for the next question
  questionsHolder.dataset.index = index + 1;

  // Add remove button
  addQuestionRemoveButton(questionItem);

  // Set up the options section with the correct structure
  setupOptionsSection(questionItem);
}

function addQuestionRemoveButton(questionItem) {
  let removeButton = questionItem.querySelector('.remove-question');

  if (!removeButton) {
    removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.classList.add('btn', 'btn-sm', 'btn-outline-danger', 'remove-question');
    removeButton.innerText = 'Remove this question';

    const cardHeader = questionItem.querySelector('.card-header');
    if (cardHeader) {
      cardHeader.appendChild(removeButton);
    }
  }

  removeButton.addEventListener('click', () => {
    questionItem.remove();
  });
}

function setupOptionsSection(questionItem) {
  const cardBody = questionItem.querySelector('.card-body');

  const optionsSection = document.createElement('div');
  optionsSection.classList.add('options-section', 'mt-3');

  const optionsHeading = document.createElement('h5');
  optionsHeading.innerText = 'Answer Options';
  optionsSection.appendChild(optionsHeading);

  const optionsList = document.createElement('ul');
  optionsList.classList.add('options', 'list-unstyled');
  optionsList.dataset.index = '0';

  // Get the question index
  const questionIndex = questionItem.getAttribute('data-question-index') || '0';

  // Try multiple strategies to find the options prototype

  // Strategy 1: Look for the options collection directly in this question's form
  const optionsCollectionInQuestion = cardBody.querySelector('[data-prototype]');

  // Strategy 2: Find an existing options collection in the DOM
  const existingOptionsCollection = document.querySelector('.options[data-prototype]');

  // Strategy 3: Look for any form element with a prototype that contains 'options'
  const anyOptionsPrototypeElement = document.querySelector('[data-prototype*="options"]');

  if (optionsCollectionInQuestion && optionsCollectionInQuestion.dataset.prototype) {
    // Found options prototype in this question
    optionsList.dataset.prototype = optionsCollectionInQuestion.dataset.prototype;
    console.log("Found options prototype in current question");
  } else if (existingOptionsCollection && existingOptionsCollection.dataset.prototype) {
    // Found options prototype in another existing question
    let prototype = existingOptionsCollection.dataset.prototype;

    // We'll use this prototype but need to modify it for the current question index
    optionsList.dataset.prototype = prototype;
    console.log("Found options prototype in existing question");
  } else if (anyOptionsPrototypeElement && anyOptionsPrototypeElement.dataset.prototype) {
    // Found some prototype that contains 'options'
    optionsList.dataset.prototype = anyOptionsPrototypeElement.dataset.prototype;
    console.log("Found options prototype in generic element");
  } else {
    console.error("Could not find any options prototype");
  }

  optionsSection.appendChild(optionsList);

  const addOptionButton = document.createElement('button');
  addOptionButton.type = 'button';
  addOptionButton.classList.add('btn', 'btn-sm', 'btn-outline-success', 'add-option-link', 'mt-2');
  addOptionButton.innerText = 'Add an answer option';
  addOptionButton.dataset.collectionHolderClass = 'options';

  addOptionButton.addEventListener('click', event => {
    addOptionToCollection(event, questionItem);
  });

  optionsSection.appendChild(addOptionButton);
  cardBody.appendChild(optionsSection);

  // Add default options
  setTimeout(() => {
    // Using setTimeout to ensure DOM is fully updated
    addOptionToCollection({ currentTarget: addOptionButton }, questionItem);
    addOptionToCollection({ currentTarget: addOptionButton }, questionItem);
  }, 100);
}

function addOptionToCollection(e, questionItem) {
  const optionsHolder = questionItem.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

  if (!optionsHolder) {
    console.error('Options holder not found');
    return;
  }

  const prototype = optionsHolder.dataset.prototype;
  if (!prototype) {
    console.error('Option prototype not found');
    return;
  }

  const index = parseInt(optionsHolder.dataset.index || 0);

  // Get the question index to ensure options are correctly associated with their question
  const questionIndex = questionItem.getAttribute('data-question-index') || '0';

  // We need to modify the prototype to ensure it has the correct question index
  // This is crucial for nested collections to work properly

  // First, extract the name pattern from the prototype
  const namePattern = prototype.match(/name="([^"]+)"/);
  if (!namePattern || !namePattern[1]) {
    console.error('Could not extract name pattern from prototype');
    return;
  }

  // Get the base name without the [__name__] part
  let namePath = namePattern[1];

  // If we're in a dynamic question, we need to ensure the question index is correct
  // Replace the question index placeholder in the name attribute if needed
  if (namePath.includes('[questions]')) {
    namePath = namePath.replace(/\[questions\]\[\d+\]/, `[questions][${questionIndex}]`);
  }

  // Now create a modified prototype with the correct question index and option index
  let newForm = prototype;

  // First, fix the name attribute to ensure it has the correct question index
  newForm = newForm.replace(/name="([^[]*\[questions\]\[\d+\]\[options\]\[)__name__(\]\[[^\]]+\])"/g,
    `name="$1${index}$2"`);

  // Then fix any IDs that might be in the form
  newForm = newForm.replace(/id="([^[]*\[questions\]\[\d+\]\[options\]\[)__name__(\]\[[^\]]+\])"/g,
    `id="$1${index}$2"`);

  // Special handling for specific fields to ensure they map to the correct properties
  newForm = newForm.replace(/name="([^[]*\[questions\]\[\d+\]\[options\]\[)(\d+)(\]\[)(label)(\])"/g,
    `name="$1$2$3label$5"`);
  newForm = newForm.replace(/name="([^[]*\[questions\]\[\d+\]\[options\]\[)(\d+)(\]\[)(code)(\])"/g,
    `name="$1$2$3code$5"`);
  newForm = newForm.replace(/name="([^[]*\[questions\]\[\d+\]\[options\]\[)(\d+)(\]\[)(isCorrect)(\])"/g,
    `name="$1$2$3isCorrect$5"`);

  // Finally replace any remaining __name__ placeholders
  newForm = newForm.replace(/__name__/g, index);

  const optionItem = document.createElement('li');
  optionItem.classList.add('option-item', 'mb-3', 'p-2', 'border', 'rounded');
  optionItem.setAttribute('data-option-index', index);

  const optionHeader = document.createElement('div');
  optionHeader.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');
  optionHeader.innerHTML = `<h6>Option ${index + 1}</h6>`;

  const removeButton = document.createElement('button');
  removeButton.type = 'button';
  removeButton.classList.add('btn', 'btn-sm', 'btn-outline-danger', 'remove-option');
  removeButton.innerText = 'Remove';
  optionHeader.appendChild(removeButton);

  // Create a temporary container to hold the form HTML
  const tempContainer = document.createElement('div');
  tempContainer.innerHTML = newForm;

  // Append the header
  optionItem.appendChild(optionHeader);

  // Now find all form elements and append them individually to maintain proper DOM structure
  const formGroups = tempContainer.querySelectorAll('.form-group, .mb-3');
  formGroups.forEach(formGroup => {
    optionItem.appendChild(formGroup);
  });

  // If no form groups were found, fallback to adding the entire HTML
  if (formGroups.length === 0) {
    optionItem.innerHTML += newForm;
  }

  // Verify field names after creation
  const fields = optionItem.querySelectorAll('input, select, textarea');
  fields.forEach(field => {
    // Ensure each field has the correct name attribute format
    // This handles cases where the regex replacements above might have missed something
    const name = field.getAttribute('name');
    if (name && name.includes('[options]')) {
      // Get the field type from the ID or name
      let fieldType = '';
      if (name.includes('[label]')) fieldType = 'label';
      else if (name.includes('[code]')) fieldType = 'code';
      else if (name.includes('[isCorrect]')) fieldType = 'isCorrect';

      console.log(`Created field ${name} of type ${fieldType} with value ${field.value}`);
    }
  });

  optionsHolder.appendChild(optionItem);

  // Update the index for the next option
  optionsHolder.dataset.index = index + 1;

  addOptionRemoveButton(optionItem);
}

function addOptionRemoveButton(optionItem) {
  const removeButton = optionItem.querySelector('.remove-option');

  if (removeButton) {
    removeButton.addEventListener('click', () => {
      optionItem.remove();
    });
  }
}

// Debug function to verify form field names and structure
function debugFormFields() {
  console.log("Debugging form fields...");

  // Debug question items
  document.querySelectorAll('.question-item').forEach((questionItem, qIndex) => {
    console.log(`Question ${qIndex}:`);

    // Debug option items for this question
    questionItem.querySelectorAll('.option-item').forEach((optionItem, oIndex) => {
      console.log(`Option ${oIndex}:`);

      // Debug form fields in this option
      optionItem.querySelectorAll('input, select, textarea').forEach(input => {
        console.log(`Field: ${input.name} = ${input.value}`);
      });
    });
  });
}

// Call the debug function after form initialization
document.addEventListener('DOMContentLoaded', () => {
  // Wait a short while to ensure all DOM operations are complete
  setTimeout(() => {
    debugFormFields();
  }, 500);
});