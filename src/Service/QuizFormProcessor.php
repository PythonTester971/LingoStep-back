<?php

namespace App\Service;

use App\Entity\Mission;
use App\Entity\Question;
use App\Entity\Option;

/**
 * Service to handle processing and validation of quiz forms
 */
class QuizFormProcessor
{
  /**
   * Process a mission entity after form submission to ensure all relationships
   * and data are valid
   *
   * @param Mission $mission
   * @return Mission
   */
  public function processMission(Mission $mission): Mission
  {
    foreach ($mission->getQuestions() as $qIndex => $question) {
      $this->processQuestion($question, $mission, $qIndex);
    }

    return $mission;
  }

  /**
   * Process a question entity and its options
   *
   * @param Question $question
   * @param Mission $mission
   * @param int $qIndex
   * @return Question
   */
  private function processQuestion(Question $question, Mission $mission, int $qIndex): Question
  {
    // Ensure question is linked to mission
    $question->setMission($mission);

    // Process each option
    foreach ($question->getOptions() as $oIndex => $option) {
      $this->processOption($option, $question, $oIndex);
    }

    return $question;
  }

  /**
   * Process an option entity, fixing any data issues
   *
   * @param Option $option
   * @param Question $question
   * @param int $oIndex
   * @return Option
   */
  private function processOption(Option $option, Question $question, int $oIndex): Option
  {
    // Ensure option is linked to question
    $option->setQuestion($question);

    // Validate and fix option data
    $this->validateOptionFields($option, $oIndex);

    return $option;
  }

  /**
   * Validate and fix option field values
   *
   * @param Option $option
   * @param int $oIndex
   * @return void
   */
  private function validateOptionFields(Option $option, int $oIndex): void
  {
    // Fix label/code mixups (when label is null but code has a value)
    if ($option->getLabel() === null) {
      if ($option->getCode() !== null) {
        // Move code value to label
        $option->setLabel($option->getCode());
        $option->setCode(null);
      } else {
        // Set a default label
        $option->setLabel("Option " . ($oIndex + 1));
      }
    }

    // Ensure isCorrect has a value
    if ($option->isCorrect() === null) {
      $option->setIsCorrect(false);
    }
  }
}
