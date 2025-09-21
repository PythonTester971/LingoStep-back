document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll('.add-question-link').forEach(btn => {
    btn.addEventListener("click", addQuestionToCollection);
  });

  document.querySelectorAll('.question-item').forEach(questionItem => {

    addQuestionRemoveButton(questionItem);

    const addOptionBtn = questionItem.querySelector('.add-option-link');
    if (addOptionBtn) {
      addOptionBtn.addEventListener('click', event => {
        addOptionToCollection(event, questionItem);
      });
    }

    questionItem.querySelectorAll('.option-item').forEach(optionItem => {
      addOptionRemoveButton(optionItem);
    });
  });
});

function addQuestionToCollection(e) {
  const questionsHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

  const prototype = questionsHolder.dataset.prototype;

  const index = parseInt(questionsHolder.dataset.index);

  const newForm = prototype.replace(/__name__/g, index);

  const questionItem = document.createElement('li');
  questionItem.classList.add('question-item', 'card', 'mb-4', 'p-3');

  const cardHeader = document.createElement('div');
  cardHeader.classList.add('card-header', 'd-flex', 'justify-content-between', 'align-items-center', 'bg-light');
  cardHeader.innerHTML = `<h4>Question ${index + 1}</h4>`;

  const cardBody = document.createElement('div');
  cardBody.classList.add('card-body');

  cardBody.innerHTML = newForm;

  questionItem.appendChild(cardHeader);
  questionItem.appendChild(cardBody);

  questionsHolder.appendChild(questionItem);

  questionsHolder.dataset.index = index + 1;

  addQuestionRemoveButton(questionItem);

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

  const formElement = cardBody.querySelector('div[data-prototype]');
  if (formElement && formElement.dataset.prototype) {
    optionsList.dataset.prototype = formElement.dataset.prototype;
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

  addOptionToCollection({ currentTarget: addOptionButton }, questionItem);
  addOptionToCollection({ currentTarget: addOptionButton }, questionItem);
}

function addOptionToCollection(e, questionItem) {

  const optionsHolder = questionItem.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

  if (!optionsHolder) {
    console.error('Options holder not found');
    return;
  }

  const prototype = optionsHolder.dataset.prototype;

  const index = parseInt(optionsHolder.dataset.index || 0);

  const newForm = prototype.replace(/__name__/g, index);

  const optionItem = document.createElement('li');
  optionItem.classList.add('option-item', 'mb-3', 'p-2', 'border', 'rounded');

  const optionHeader = document.createElement('div');
  optionHeader.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');
  optionHeader.innerHTML = `<h6>Option ${index + 1}</h6>`;

  const removeButton = document.createElement('button');
  removeButton.type = 'button';
  removeButton.classList.add('btn', 'btn-sm', 'btn-outline-danger', 'remove-option');
  removeButton.innerText = 'Remove';
  optionHeader.appendChild(removeButton);

  optionItem.appendChild(optionHeader);
  optionItem.innerHTML += newForm;

  optionsHolder.appendChild(optionItem);

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