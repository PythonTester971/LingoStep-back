<?php

namespace App\Twig;

use App\Repository\LanguageCourseRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageCourseExtension extends AbstractExtension
{
  private LanguageCourseRepository $languageCourseRepository;

  public function __construct(LanguageCourseRepository $languageCourseRepository)
  {
    $this->languageCourseRepository = $languageCourseRepository;
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('get_language_courses', [$this, 'getLanguageCourses']),
    ];
  }

  public function getLanguageCourses(): array
  {
    return $this->languageCourseRepository->findBy([], ['label' => 'ASC']);
  }
}
